# .github/scripts/reviewer.py

import os
import sys
import subprocess
import google.generativeai as genai
from github import Github

def main():
    """
    Função principal que orquestra o processo de revisão de código.
    """
    # --- CONFIGURAÇÃO E OBTENÇÃO DE DADOS ---
    try:
        github_token = os.environ['GITHUB_TOKEN']
        gemini_api_key = os.environ['GEMINI_API_KEY']
        pr_number = int(os.environ['PR_NUMBER'])
        repo_name = os.environ['GITHUB_REPOSITORY']
        base_sha = os.environ['BASE_SHA']
        head_sha = os.environ['HEAD_SHA']
    except KeyError as e:
        print(f"Erro: A variável de ambiente {e} não foi definida.")
        sys.exit(1)

    # Configurar a API do Gemini
    try:
        genai.configure(api_key=gemini_api_key)
        llm = genai.GenerativeModel('gemini-1.5-flash')
    except Exception as e:
        print(f"Erro ao configurar a API do Gemini: {e}")
        sys.exit(1)

    print("Iniciando o processo de revisão de código por IA...")

    # --- OBTENÇÃO DO DIFF DO CÓDIGO ---
    code_diff = get_code_diff(base_sha, head_sha)
    if not code_diff:
        print("Não foi possível obter o diff do código. Encerrando.")
        sys.exit(1)

    # --- ANÁLISE PELA IA ---
    print("Diff obtido. Solicitando revisão da IA...")
    ai_feedback = get_ai_review(llm, code_diff)
    if not ai_feedback:
        print("Não foi possível obter feedback da IA. Encerrando.")
        sys.exit(1)

    # --- POSTAGEM DO COMENTÁRIO NO PR ---
    final_comment = f"### 🤖 Revisão Automática por IA\n\n{ai_feedback}"
    post_comment_on_pr(github_token, repo_name, pr_number, final_comment)

    # --- VEREDITO FINAL (PASS/FAIL) ---
    print("Analisando o veredito da IA para o status check...")
    if "[STATUS: FAIL]" in ai_feedback:
        print("!!! Veredito da IA: REJEITADO. Encontrados problemas críticos.")
        sys.exit(1)  # Falha o workflow para bloquear o merge
    elif "[STATUS: PASS]" in ai_feedback:
        print("Veredito da IA: APROVADO. Nenhum problema crítico encontrado.")
        sys.exit(0)  # Workflow concluído com sucesso
    else:
        print("AVISO: Não foi possível determinar um status [PASS/FAIL] claro na resposta da IA.")
        # Decide se deve falhar ou passar por padrão. Passar é mais seguro para não bloquear indevidamente.
        sys.exit(0)

def get_code_diff(base_sha, head_sha):
    """
    Obtém o diff do código entre o branch base e o head do PR usando o git.
    """
    try:
        diff_command = ["git", "diff", f"{base_sha}...{head_sha}"]
        result = subprocess.run(diff_command, capture_output=True, text=True, check=True)
        return result.stdout
    except subprocess.CalledProcessError as e:
        print(f"Erro ao obter o diff do código: {e}\n{e.stderr}")
        return None

def get_ai_review(llm, diff_text):
    """
    Envia o diff para o LLM e pede uma revisão de código estruturada.
    """
    if not diff_text or len(diff_text.encode('utf-8')) > 1000000: # Limite de segurança
        print("Diff muito grande ou vazio. Pulando revisão da IA.")
        return "O diff do código é muito grande ou está vazio para ser analisado pela IA."

    prompt = f"""
    Você é um revisor de código sênior de Python, extremamente rigoroso, especializado em encontrar bugs e vulnerabilidades de segurança.
    Sua tarefa é revisar o seguinte 'diff' de um pull request.

    Analise os seguintes pontos com atenção máxima:
    1.  **Vulnerabilidades de Segurança CRÍTICAS:** Injeção de SQL, vazamento de segredos, falhas de autenticação, etc.
    2.  **Bugs Lógicos GRAVES:** Condições de corrida, loops infinitos, lógica que pode levar a crashes.
    3.  **Más Práticas SÉRIAS:** Uso de bibliotecas depreciadas, código excessivamente complexo que pode esconder bugs.

    Forneça seu feedback em formato Markdown, sendo construtivo mas direto.

    **IMPORTANTE:** No final da sua revisão, adicione uma linha de status.
    - Se você encontrar QUALQUER vulnerabilidade de segurança, bug grave ou má prática séria, termine com a linha: `[STATUS: FAIL]`
    - Se o código estiver limpo, seguro e seguir as boas práticas, termine com a linha: `[STATUS: PASS]`

    Aqui está o diff do código:
    ```diff
    {diff_text}
    ```

    Sua revisão (terminando com a linha de status):
    """

    try:
        response = llm.generate_content(prompt)
        return response.text
    except Exception as e:
        print(f"Erro ao chamar a API do Gemini: {e}")
        return f"Desculpe, ocorreu um erro ao tentar revisar o código com a IA. Detalhes: {e}\n\n[STATUS: FAIL]"

def post_comment_on_pr(token, repo_name, pr_number, comment_body):
    """
    Posta o feedback como um comentário no Pull Request.
    """
    try:
        g = Github(token)
        repo = g.get_repo(repo_name)
        pr = repo.get_pull(pr_number)
        pr.create_issue_comment(comment_body)
        print("Comentário postado com sucesso no PR.")
    except Exception as e:
        print(f"Erro ao postar o comentário no PR: {e}")
        # Não falha a build por não conseguir comentar, a falha do status check é mais importante.

if __name__ == "__main__":
    main()