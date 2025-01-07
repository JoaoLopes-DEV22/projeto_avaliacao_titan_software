<?php
// Inicia a sessão para acessar os dados armazenados
session_start();

// Destrói todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para a página de login após o logout
header("Location: ../index.php");
exit;
