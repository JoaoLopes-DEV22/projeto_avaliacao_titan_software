<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Conexão com o banco de dados
require_once 'connection.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    echo "Erro ao conectar ao banco de dados.";
    exit;
}

// Verifica se o ID foi enviado via POST
if (isset($_POST['id_funcionario'])) {
    $id_funcionario = $_POST['id_funcionario'];

    try {
        // Query para deletar o funcionário
        $sql = "DELETE FROM tbl_funcionario WHERE id_funcionario = :id_funcionario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_funcionario', $id_funcionario);

        // Executar a query
        if ($stmt->execute()) {
            $_SESSION['message_user_delete'] = [
                'tipo' => 'success',
                'conteudo' => 'Funcionário excluído com sucesso!'
            ];
        } else {
            $_SESSION['message_user_delete'] = [
                'tipo' => 'error',
                'conteudo' => 'Erro ao excluir o funcionário!'
            ];
        }

        // Redireciona de volta para a página de listagem
        header("Location: ../pages/logged.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['message_user_delete'] = [
            'tipo' => 'error',
            'conteudo' => 'Erro ao excluir o funcionário: ' . $e->getMessage()
        ];
        header("Location: ../pages/logged.php");
        exit;
    }
} else {
    $_SESSION['message_user_delete'] = [
        'tipo' => 'error',
        'conteudo' => 'ID do funcionário não foi enviado.'
    ];
    header("Location: ../pages/logged.php");
    exit;
}
