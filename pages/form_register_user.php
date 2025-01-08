<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Verifica se há uma mensagem de sucesso ou erro na sessão e define uma variável local para usá-la
$mensagem = isset($_SESSION['message_user']) ? $_SESSION['message_user'] : null;

// Limpa a sessão de mensagem após exibi-la
unset($_SESSION['message_user']);

// Conexão com o banco de dados
require_once '../php-action/connection.php';

$database = new Database();
$conn = $database->getConnection();

if (!$conn) {
    echo "Erro ao conectar ao banco de dados.";
    exit;
}

try {
    // Query para buscar todas as empresas
    $sql = "SELECT id_empresa, nome FROM tbl_empresa";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Resultado do banco de dados
    $empresas = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erro ao buscar empresas: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Funcionário</title>
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
            <img src="https://cdn-icons-png.flaticon.com/512/1144/1144760.png" style="filter: invert(1);" alt="Ícone de tecnologia">
        </div>

        <!-- Título -->
        <h2>Cadastrar Funcionário</h2>

        <!-- Exibe a mensagem de sucesso ou erro, caso exista -->
        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>" role="alert">
                <?php echo htmlspecialchars($mensagem['conteudo'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário de cadastro -->
        <form action="../php-action/register_user.php" method="POST">

            <!-- Input de Nome -->
            <div class="form-group">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Digite seu nome completo" required>
            </div>

            <!-- Input de E-mail -->
            <div class="form-group">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu melhor e-mail" required>
            </div>

            <!-- Input de CPF -->
            <div class="form-group">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" id="cpf" name="cpf" class="form-control" minlength="11" maxlength="11" placeholder="Digite o seu CPF" required>
            </div>

            <!-- Input de RG -->
            <div class="form-group">
                <label for="rg" class="form-label">Registro Geral (RG):</label>
                <input type="text" id="rg" name="rg" class="form-control" maxlength="20" placeholder="Digite seu RG">
            </div>

            <!-- Select de Empresa -->
            <div class="form-group">
                <label for="company" class="form-label">Empresa:</label>
                <select name="company" id="company" class="form-control" required>
                    <option value="">Selecione uma empresa</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo htmlspecialchars($empresa['id_empresa'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botão de Cadastro -->
            <button type="submit" class="btn-login">Cadastrar</button>
        </form>
    </div>
</body>

</html>
