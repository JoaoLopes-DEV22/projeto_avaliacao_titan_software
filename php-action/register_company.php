<?php
session_start();
require_once 'connection.php';

// Verifica se o nome da empresa foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $nomeEmpresa = trim($_POST['name']);

    // Validação do campo
    if (empty($nomeEmpresa)) {
        $_SESSION['message'] = [
            'tipo' => 'error',
            'conteudo' => 'Campo nome é obrigatório.'
        ];
        header('Location: ../pages/form_register_company.php');
        exit;
    }

    // Conexão com o banco
    $database = new Database();
    $conn = $database->getConnection();

    if (!$conn) {
        $_SESSION['message'] = [
            'tipo' => 'error',
            'conteudo' => 'Erro ao conectar ao banco de dados.'
        ];
        header('Location: ../pages/form_register_company.php');
        exit;
    }

    try {
        // Prepara a query de inserção
        $sql = "INSERT INTO tbl_empresa (nome) VALUES (:nome)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nomeEmpresa, PDO::PARAM_STR);

        // Executa a query
        $stmt->execute();

        // Define mensagem de sucesso
        $_SESSION['message'] = [
            'tipo' => 'success',
            'conteudo' => 'Empresa cadastrada com sucesso!'
        ];
    } catch (PDOException $e) {
        // Define mensagem de erro
        $_SESSION['message'] = [
            'tipo' => 'error',
            'conteudo' => 'Erro ao cadastrar a empresa: ' . $e->getMessage()
        ];
    }

    // Redireciona de volta para o formulário
    header('Location: ../pages/form_register_company.php');
    exit;
} else {
    // Mensagem caso o método não seja POST ou o campo esteja ausente
    $_SESSION['message'] = [
        'tipo' => 'error',
        'conteudo' => 'Envie os dados pelo formulário corretamente.'
    ];
    header('Location: ../pages/form_register_company.php');
    exit;
}
