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
          JOIN tbl_empresa e ON f.id_empresa = e.id_empresa ORDER BY f.nome ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclui a biblioteca TCPDF
require_once '../vendor/autoload.php';

// Cria o objeto TCPDF
$pdf = new TCPDF();

// Define as propriedades do documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('João Victor Lopes');
$pdf->SetTitle('Funcionários');
$pdf->SetSubject('Lista de Funcionários');

// Adiciona uma página na orientação paisagem
$pdf->AddPage('L');

// Define o título
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Lista de Funcionarios', 0, 1, 'C');

// Define a tabela de funcionários
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(10);

// Cabeçalho da tabela
$pdf->SetFillColor(64, 64, 121);

$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(15, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Nome', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'E-mail', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Empresa', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Data Cadastro', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'CPF', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'RG', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Bonificação', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Salário', 1, 1, 'C', true);

// Dados dos funcionários
foreach ($funcionarios as $funcionario) {
    $bonificacao = ($funcionario['bonificacao']);
    $salarioTotal = $funcionario['salario'] + $bonificacao;

    // Formata a data de cadastro
    $dataCadastro = new DateTime($funcionario['data_cadastro']);
    $dataCadastroFormatada = $dataCadastro->format('d/m/Y');

    // CPF e RG
    $cpf = !empty($funcionario['cpf']) ? $funcionario['cpf'] : 'Não informado';
    $rg = !empty($funcionario['rg']) ? $funcionario['rg'] : 'Não informado';

    // Preenche os dados do funcionário na tabela
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(15, 10, $funcionario['id_funcionario'], 1, 0, 'C');
    $pdf->Cell(30, 10, $funcionario['nome'], 1, 0, 'C');
    $pdf->Cell(50, 10, $funcionario['email'], 1, 0, 'C');
    $pdf->Cell(30, 10, $funcionario['nome_empresa'], 1, 0, 'C');
    $pdf->Cell(30, 10, $dataCadastroFormatada, 1, 0, 'C');
    $pdf->Cell(30, 10, $cpf, 1, 0, 'C');
    $pdf->Cell(30, 10, $rg, 1, 0, 'C');
    $pdf->Cell(30, 10, 'R$ ' . number_format($bonificacao, 2, ',', '.'), 1, 0, 'C');
    $pdf->Cell(30, 10, 'R$ ' . number_format($salarioTotal, 2, ',', '.'), 1, 1, 'C');
}

// Output do PDF (exibe no navegador)
$pdf->Output('funcionarios.pdf', 'I');
