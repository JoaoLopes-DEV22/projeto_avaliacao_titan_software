<?php
session_start();

// Verifica se há uma mensagem de sucesso ou erro na sessão e define uma variável local para usá-la
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem']  null;

// Limpa a sessão de mensagem após exibi-la
unset($_SESSION'mensagem']);

// Verifica se o usuário já está logado, se estiver, redireciona para a página logged.php
if (isset($_SESSION['usuario'])) {
    header("Location: pages/logged.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="login-container">

        <!-- Ícone ou logotipo -->
        <div class="icon">
            <img src="https://cdn-icons-png.flaticon.com/256/3073/3073441.png" alt="Ícone de tecnologia">
        </div>

        <!-- Título -->
        <h2>Bem-vindo</h2>

        <!-- Exibe a mensagem de sucesso ou erro, caso exista -->
        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>" role="alert">
                <?php echo htmlspecialchars($mensagem['conteudo'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de login -->
        <form action="php-action/auth.php" method="POST">

            <!-- Input de E-mail -->
            <div class="form-group">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Digite seu e-mail">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Senha</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha">
            </div>

            <!-- Botão de submit -->
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        <p class="text-muted">Não tem uma conta? <a href="#">Cadastre-se</a></p>
    </div>
</body>

</html>