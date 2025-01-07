<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Verifica se há uma mensagem de sucesso ou erro na sessão e define uma variável local para usá-la
$mensagem = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpa a sessão de mensagem após exibi-la
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empresa</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="login-container">
        <!-- Ícone de voltar -->
        <div class="back">
            <a href="../pages/logged.php">
                <img src="https://cdn-icons-png.flaticon.com/512/93/93634.png" alt="Ícone de voltar">
            </a>
        </div>

        <!-- Ícone ou logotipo -->
        <div class="icon">
            <img src="https://cdn-icons-png.flaticon.com/512/4844/4844589.png" alt="Ícone de tecnologia">
        </div>

        <!-- Título -->
        <h2>Cadastrar Empresa</h2>

        <!-- Exibe a mensagem de sucesso ou erro, caso exista -->
        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>" role="alert">
                <?php echo htmlspecialchars($mensagem['conteudo'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de Cadastrar Empresa -->
        <form action="../php-action/register_company.php" method="POST">
            <div class="form-group">
                <label for="name" class="form-label">Nome</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Digite o nome da empresa">
            </div>
            <button type="submit" class="btn-login">Cadastrar</button>
        </form>

    </div>
</body>

</html>