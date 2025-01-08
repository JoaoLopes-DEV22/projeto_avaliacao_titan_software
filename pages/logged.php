<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Verifica se há uma mensagem de sucesso ou erro na sessão e define uma variável local para usá-la
$mensagem = isset($_SESSION['message_user_delete']) ? $_SESSION['message_user_delete'] : null;

// Limpa a sessão de mensagem após exibi-la
unset($_SESSION['message_user_delete']);

// Conexão com o banco de dados
require_once '../php-action/connection.php';
$database = new Database();
$db = $database->getConnection();

// Consulta para obter todos os funcionários com o nome da empresa
$query = "SELECT f.*, e.nome AS nome_empresa FROM tbl_funcionario f
          JOIN tbl_empresa e ON f.id_empresa = e.id_empresa ORDER BY f.nome ASC";
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
            <span>Bem-vindo, Admin!</span>
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
            <a href="form_user.php" class="button">Cadastrar Funcionário</a>
            <a href="form_register_company.php" class="button">Cadastrar Empresa</a>
        </div>

        <!-- Botões de Exportar -->
        <div class="content-export">
            <a href="../php-action/export_pdf.php" target="_blank" class="button export">Exportar</a>
        </div>

        <!-- Exibe a mensagem de sucesso ou erro, caso exista -->
        <?php if ($mensagem): ?>
            <div class="alert-area">
                <div class="alert alert-<?php echo $mensagem['tipo']; ?>" role="alert">
                    <?php echo htmlspecialchars($mensagem['conteudo'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        <?php endif; ?>

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
                    <th>Cadastro</th>
                    <th>Bonificação</th>
                    <th>Salário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($funcionarios) > 0): ?>
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <?php
                        // Calculando o tempo de trabalho na empresa (em anos)
                        $dataCadastro = new DateTime($funcionario['data_cadastro']);
                        $hoje = new DateTime();
                        $intervalo = $dataCadastro->diff($hoje);
                        $anosDeEmpresa = $intervalo->y;

                        // Definindo a bonificação e a cor da linha
                        if ($anosDeEmpresa > 5) {
                            $bonificacao = $funcionario['salario'] * 0.20;
                            $corLinha = '#a32828'; // Linha vermelha para mais de 5 anos
                        } elseif ($anosDeEmpresa > 1) {
                            $bonificacao = $funcionario['salario'] * 0.10;
                            $corLinha = '#1717b3de'; // Linha azul para mais de 1 ano
                        } else {
                            $bonificacao = $funcionario['bonificacao'];
                            $corLinha = ''; // Cor padrão
                        }

                        // Calculando o salário total
                        $salarioTotal = $funcionario['salario'] + $bonificacao;
                        ?>

                        <tr style="background-color: <?php echo $corLinha; ?>;">
                            <td><?php echo htmlspecialchars($funcionario['id_funcionario']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['email']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['nome_empresa']); ?></td>
                            <td><?php echo htmlspecialchars($funcionario['cpf']); ?></td>
                            <td>
                                <?php
                                echo !empty($funcionario['rg']) ? $funcionario['rg'] : 'Não informado';
                                ?>
                            </td>
                            <td><?php echo (new DateTime($funcionario['data_cadastro']))->format('d/m/Y'); ?></td>
                            <td>R$<?php echo number_format($bonificacao, 2, ',', '.'); ?></td>
                            <td>R$<?php echo number_format($salarioTotal, 2, ',', '.'); ?></td>
                            <td class="btn-actions">
                                <a href="form_user.php?id=<?php echo $funcionario['id_funcionario']; ?>" class="button edit">Editar</a>
                                <a href="#" class="button button-exclude" data-id="<?php echo $funcionario['id_funcionario']; ?>">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">Nenhum funcionário cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Modal de Confirmação -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h3>Confirmar Exclusão</h3>
                <p>Você tem certeza que deseja excluir este funcionário?</p>
                <div class="modal-actions">
                    <button id="cancelButton" class="btn cancel">Cancelar</button>
                    <form id="deleteForm" action="../php-action/delete_user.php" method="POST">
                        <input type="hidden" name="id_funcionario" id="id_funcionario">
                        <button type="submit" class="btn confirm">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <footer>
        Desenvolvido por João Victor Lopes
    </footer>

    <script src="../js/script.js" defer></script>
</body>

</html>