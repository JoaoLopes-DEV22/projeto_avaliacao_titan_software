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
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'] ?? 'Não informado';
    $id_empresa = $_POST['company'];

    // Validação simples
    if (empty($nome) || empty($email) || empty($cpf) || empty($id_empresa)) {
        $_SESSION['message_user'] = [
            'tipo' => 'error',
            'conteudo' => 'Todos os campos são obrigatórios!'
        ];
        header("Location: ../pages/form_register_user.php");
        exit;
    }

    try {
        // Preparar a query de inserção
        $sql = "INSERT INTO tbl_funcionario (nome, cpf, rg, email, id_empresa) 
                VALUES (:nome, :cpf, :rg, :email, :id_empresa)";
        $stmt = $conn->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_empresa', $id_empresa);

        // Executar a query
        if ($stmt->execute()) {
            $_SESSION['message_user'] = [
                'tipo' => 'success',
                'conteudo' => 'Funcionário cadastrado com sucesso!'
            ];
        } else {
            $_SESSION['message_user'] = [
                'tipo' => 'error',
                'conteudo' => 'Erro ao cadastrar o funcionário!'
            ];
        }

        // Redireciona para a página de cadastro de funcionário
        header("Location: ../pages/form_register_user.php");
    } catch (PDOException $e) {
        $_SESSION['message_user'] = [
            'tipo' => 'error',
            'conteudo' => 'Erro ao cadastrar o funcionário: ' . $e->getMessage()
        ];
        header("Location: ../pages/form_register_user.php");
        exit;
    }
}
