<?php
session_start();

// Arquivo de conexão com o banco de dados
require_once 'connection.php';

try {
    // Cria uma instância da classe Database e estabelece a conexão com o banco de dados
    $database = new Database();
    $dbConnection = $database->getConnection();

    // Verifica se a conexão foi bem-sucedida
    if (!$dbConnection) {
        throw new Exception("Erro ao conectar ao banco de dados.");
    }

    // Verifica se o formulário foi submetido corretamente
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método inválido. Utilize o método POST.");
    }

    // Obtém os dados do formulário
    $login = trim($_POST['email']);
    $senha = trim($_POST['password']);

    // Verifica se os campos foram preenchidos
    if (empty($login) || empty($senha)) {
        throw new Exception("Usuário e senha são obrigatórios.");
    }

    // Consulta para verificar as credenciais do usuário
    $query = "SELECT id_usuario, login, senha FROM tbl_usuario WHERE login = :login";
    $stmt = $dbConnection->prepare($query);
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    // Verifica se o usuário foi encontrado
    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();

        // Verifica se a senha fornecida é igual à senha armazenada no banco de dados
        if ($senha === $user['senha']) {

            // Armazena os dados do usuário na sessão
            $_SESSION['usuario'] = [
                'id' => $user['id_usuario'],
                'login' => $user['login']
            ];

            // Mensagem de sucesso e redirecionamento
            $_SESSION['mensagem'] = [
                'tipo' => 'sucesso',
                'conteudo' => 'Login realizado com sucesso!'
            ];

            header("Location: ../pages/logged.php");
            exit;
        } else {
            // Se a senha não corresponder
            throw new Exception("Usuário ou senha inválidos.");
        }
    } else {
        // Se o login não for encontrado
        throw new Exception("Usuário ou senha inválidos.");
    }
} catch (Exception $e) {

    // Armazena mensagem de erro e redireciona para a página de login
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'conteudo' => $e->getMessage()
    ];
    
    header("Location: ../index.php");
    exit;
}
