<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Conexão com o banco de dados
require_once '../php-action/connection.php';
$database = new Database();
$db = $database->getConnection();

// Consulta para obter todos os funcionários com o nome da empresa
$query = "SELECT f.*, e.nome AS nome_empresa FROM tbl_funcionario f
          JOIN tbl_empresa e ON f.id_empresa = e.id_empresa ORDER BY f.nome DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="../css/logged.css">
</head>

<body>

    <div class="navbar">
        <div class="user-info">
            <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']['login']); ?>!</span>
        </div>
        <div class="logout">
            <a href="../php-action/logout.php" class="button">Sair</a>
        </div>
    </div>

    <div class="container">
        <h1>Página Inicial - Funcionários</h1>
        <p>Gerencie os funcionários cadastrados no sistema.</p>

        <!-- Botões de Ação -->
        <div class="content">
            <a href="form_register_user.php" class="button">Cadastrar Funcionário</a>
            <a href="form_register_company.php" class="button">Cadastrar Empresa</a>
        </div>

        <!-- Tabela de Funcionários -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Empresa</th>
                    <th>CPF</th>
                    <th>RG</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($funcionarios) > 0): ?>
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($funcionario['id_funcionario']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['email']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['nome_empresa']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['rg']); ?></td>
                            <td class="btn-actions">
                                <a href="#?id=<?php echo $funcionario['id_funcionario']; ?>" class="button">Edit</a>
                                <a href="#?id=<?php echo $funcionario['id_funcionario']; ?>" class="button button-exclude">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum funcionário cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <footer>
        Desenvolvido por João Victor Lopes
    </footer>
</body>

</html>