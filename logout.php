<?php
session_start(); // Iniciar sessão para poder destruí-la

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Destruir a sessão
session_destroy();

// Redirecionar para a página inicial
header("Location: index.php");
exit;
?>