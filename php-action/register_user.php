<?php
session_start();

// Verifica se há uma mensagem de sucesso ou erro na sessão e define uma variável local para usá-la
$mensagem = isset($_SESSION['message_user']) ? $_SESSION['message_user'] : null;

// Limpa a sessão de mensagem após exibi-la
unset($_SESSION['message_user']);

// Conexão com o banco de dados
require_once 'connection.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    echo "Erro ao conectar ao banco de dados.";
    exit;
}

// Verifica se os dados do formulário foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Recebe os dados do formulário
    $id_funcionario = $_POST['id_funcionario'] ?? null;
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'] ?? 'Não informado';
    $id_empresa = $_POST['company'];
    $date = $_POST['date'];
    $salario = $_POST['salario'];
    $bonificacao = 0;

    // Validação simples
    if (empty($nome) || empty($email) || empty($cpf) || empty($id_empresa) || empty($date) || empty($salario)) {
        $_SESSION['message_user'] = [
            'tipo' => 'error',
            'conteudo' => 'Todos os campos são obrigatórios!'
        ];
        header("Location: ../pages/form_user.php");
        exit;
    }

    // Cálculo da bonificação com base na data de cadastro
    $dataCadastro = new DateTime($date);
    $hoje = new DateTime();
    $intervalo = $dataCadastro->diff($hoje);
    $anosDeEmpresa = $intervalo->y;

    if ($anosDeEmpresa > 5) {
        $bonificacao = $salario * 0.20;  // Bonificação de 20% para mais de 5 anos
    } elseif ($anosDeEmpresa > 1) {
        $bonificacao = $salario * 0.10;  // Bonificação de 10% para mais de 1 ano
    } else {
        $bonificacao = 0;  // Sem bonificação para menos de 1 ano
    }

    try {
        if ($id_funcionario) {
            // Atualiza o funcionário existente
            $sql = "UPDATE tbl_funcionario 
                    SET nome = :nome, cpf = :cpf, rg = :rg, email = :email, id_empresa = :id_empresa, data_cadastro = :data_cadastro, salario = :salario, bonificacao = :bonificacao
                    WHERE id_funcionario = :id_funcionario";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_funcionario', $id_funcionario);
        } else {
            // Insere um novo funcionário
            $sql = "INSERT INTO tbl_funcionario (nome, cpf, rg, email, id_empresa, data_cadastro, salario, bonificacao) 
                    VALUES (:nome, :cpf, :rg, :email, :id_empresa, :data_cadastro, :salario, :bonificacao)";
            $stmt = $conn->prepare($sql);
        }

        // Bind dos parâmetros comuns
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_empresa', $id_empresa);
        $stmt->bindParam(':data_cadastro', $date);
        $stmt->bindParam(':salario', $salario);
        $stmt->bindParam(':bonificacao', $bonificacao);

        // Executar a query
        if ($stmt->execute()) {
            $_SESSION['message_user'] = [
                'tipo' => 'success',
                'conteudo' => $id_funcionario ? 'Funcionário atualizado com sucesso!' : 'Funcionário cadastrado com sucesso!'
            ];
        } else {
            $_SESSION['message_user'] = [
                'tipo' => 'error',
                'conteudo' => $id_funcionario ? 'Erro ao atualizar o funcionário!' : 'Erro ao cadastrar o funcionário!'
            ];
        }

        // Redireciona para a página de cadastro de funcionário
        header("Location: ../pages/form_user.php");
    } catch (PDOException $e) {
        $_SESSION['message_user'] = [
            'tipo' => 'error',
            'conteudo' => 'Erro ao processar o funcionário: ' . $e->getMessage()
        ];
        header("Location: ../pages/form_user.php");
        exit;
    }
}
