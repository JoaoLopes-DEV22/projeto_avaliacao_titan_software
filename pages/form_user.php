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

// Inicializa variáveis do formulário
$id = null;
$name = '';
$email = '';
$cpf = '';
$rg = '';
$company = '';
$date = '';
$salario = '';

// Verifica se há um ID na URL para edição
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        // Busca os dados do funcionário no banco
        $sql = "SELECT * FROM tbl_funcionario WHERE id_funcionario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $funcionario = $stmt->fetch();
            $name = $funcionario['nome'];
            $email = $funcionario['email'];
            $cpf = $funcionario['cpf'];
            $rg = $funcionario['rg'];
            $company = $funcionario['id_empresa'];
            $date = $funcionario['data_cadastro'];
            $salario = $funcionario['salario'];
        } else {
            echo "Funcionário não encontrado.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro ao buscar dados do funcionário: " . $e->getMessage();
        exit;
    }
}

// Query para buscar todas as empresas
try {
    $sql = "SELECT id_empresa, nome FROM tbl_empresa";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
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
    <title><?php echo $id ? 'Editar Funcionário' : 'Cadastrar Funcionário'; ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="login-container">

        <div class="back">
            <a href="../pages/logged.php">
                <img src="https://cdn-icons-png.flaticon.com/512/93/93634.png" alt="Ícone de voltar">
            </a>
        </div>

        <div class="icon">
            <img src="https://cdn-icons-png.flaticon.com/512/1144/1144760.png" style="filter: invert(1);" alt="Ícone de tecnologia">
        </div>

        <h2><?php echo $id ? 'Editar Funcionário' : 'Cadastrar Funcionário'; ?></h2>

        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>" role="alert">
                <?php echo htmlspecialchars($mensagem['conteudo'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="../php-action/register_user.php" method="POST">

            <input type="hidden" name="id_funcionario" value="<?php echo isset($funcionario) ? htmlspecialchars($funcionario['id_funcionario'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Digite seu nome completo" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Digite seu melhor e-mail" required>
            </div>

            <div class="form-group">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" id="cpf" name="cpf" class="form-control" value="<?php echo htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'); ?>" minlength="11" maxlength="11" placeholder="Digite o seu CPF" required>
            </div>

            <div class="form-group">
                <label for="rg" class="form-label">Registro Geral (RG):</label>
                <input type="text" id="rg" name="rg" class="form-control" value="<?php echo htmlspecialchars($rg, ENT_QUOTES, 'UTF-8'); ?>" maxlength="20" placeholder="Digite seu RG">
            </div>

            <div class="form-group">
                <label for="company" class="form-label">Empresa:</label>
                <select name="company" id="company" class="form-control" required>
                    <option value="">Selecione uma empresa</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo htmlspecialchars($empresa['id_empresa'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $empresa['id_empresa'] == $company ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date" class="form-label">Data de Cadastro:</label>
                <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="salario" class="form-label">Salário inicial:</label>
                <input type="number" id="salario" name="salario" min="1" class="form-control" placeholder="Digite o salario inicial" value="<?php echo htmlspecialchars($salario, ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <button type="submit" class="btn-login"><?php echo $id ? 'Salvar Alterações' : 'Cadastrar'; ?></button>
        </form>
    </div>
</body>

</html>