import os
import sys
import subprocess
import google.generativeai as genai
from github import Github

# --- CONFIGURAÇÃO ---
try:
    # Chaves de API e informações do PR via variáveis de ambiente
    github_token = os.environ['GITHUB_TOKEN']
    gemini_api_key = os.environ['GEMINI_API_KEY']
    pr_number = int(os.environ['PR_NUMBER'])
    repo_name = os.environ['GITHUB_REPOSITORY']
    base_sha = os.environ['BASE_SHA']
    head_sha = os.environ['HEAD_SHA']
except KeyError as e:
    print(f"Erro: Variável de ambiente não encontrada - {e}")
    sys.exit(1)

# Configurar a API do Gemini
genai.configure(api_key=gemini_api_key)
llm = genai.GenerativeModel('gemini-1.5-flash') # ou um modelo mais avançado

# --- FUNÇÕES ---

def get_code_diff():
    """Obtém o diff do código entre o branch base e o head do PR."""
    try:
        # Usando git para obter o diff, que é o formato ideal para análise de PR
        diff_command = ["git", "diff", f"{base_sha}...{head_sha}"]
        result = subprocess.run(diff_command, capture_output=True, text=True, check=True)
        return result.stdout
    except subprocess.CalledProcessError as e:
        print(f"Erro ao obter o diff do código: {e}")
        return None

def run_static_analysis(diff_text):
    """
    (Opcional, mas recomendado)
    Aqui você poderia adicionar análise estática com Flake8, Bandit, etc.
    Por simplicidade, este exemplo focará na revisão por IA.
    """
    # Exemplo: Poderia rodar o Bandit nos arquivos alterados.
    # Por agora, retornamos uma string vazia.
    return ""

def get_ai_review(diff_text):
    """Envia o diff para o LLM e pede uma revisão de código."""
    if not diff_text or len(diff_text) > 30000: # Limite de segurança para o tamanho do diff
        print("Diff muito grande ou vazio. Pulando revisão da IA.")
        return "O diff do código é muito grande para ser analisado pela IA ou está vazio."

    # Este é o "prompt engineering" - a parte mais importante!
    prompt = f"""
    Você é um revisor de código sênior de Python, especializado em encontrar bugs,
    vulnerabilidades de segurança e garantir as melhores práticas.
    Sua tarefa é revisar o seguinte 'diff' de um pull request.

    Forneça seu feedback em formato Markdown.
    Se não houver problemas, elogie o autor.
    Se houver problemas, seja construtivo e claro.
    Foque nos seguintes pontos:
    1.  **Vulnerabilidades de Segurança:** Injeção de SQL, XSS, segredos hardcoded, etc.
    2.  **Bugs Lógicos:** Condições de corrida, erros de lógica, null pointer exceptions.
    3.  **Boas Práticas:** Código limpo, legibilidade, simplicidade (KISS), não se repita (DRY).
    4.  **Performance:** Loops ineficientes, queries de banco de dados desnecessárias.
    5.  **Estilo de Código:** Conformidade com a PEP 8, mas foque mais nos problemas lógicos do que em estilo.

    Aqui está o diff do código:
    ```diff
    {diff_text}
    ```

    Por favor, forneça sua revisão:
    """

    try:
        response = llm.generate_content(prompt)
        return response.text
    except Exception as e:
        print(f"Erro ao chamar a API do Gemini: {e}")
        return f"Desculpe, ocorreu um erro ao tentar revisar o código com a IA. Detalhes: {e}"

def post_comment_on_pr(comment_body):
    """Posta o feedback como um comentário no Pull Request."""
    try:
        g = Github(github_token)
        repo = g.get_repo(repo_name)
        pr = repo.get_pull(pr_number)
        pr.create_issue_comment(comment_body)
        print("Comentário postado com sucesso no PR.")
    except Exception as e:
        print(f"Erro ao postar o comentário no PR: {e}")
        sys.exit(1)

# --- EXECUÇÃO PRINCIPAL ---

if __name__ == "__main__":
    print("Iniciando o processo de revisão de código por IA...")

    code_diff = get_code_diff()

    if not code_diff:
        print("Não foi possível obter o diff do código. Encerrando.")
        sys.exit(1)

    print("Diff obtido. Solicitando revisão da IA...")
    ai_feedback = get_ai_review(code_diff)

    # (Opcional) Combine o feedback da IA com a análise estática
    static_feedback = run_static_analysis(code_diff)
    
    final_comment = f"### 🤖 Revisão Automática por IA\n\n"
    final_comment += f"{ai_feedback}\n\n"
    
    if static_feedback:
        final_comment += f"--- \n### 🔍 Análise Estática\n\n{static_feedback}"
        
    final_comment += "\n\n*Este comentário foi gerado por uma IA. Verifique as sugestões antes de aplicá-las.*"

    post_comment_on_pr(final_comment)
    print("Revisão concluída.")