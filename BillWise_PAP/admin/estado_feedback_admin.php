<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

require_once 'acesso_admin.php';
requireAdminApiAccess();

// Gestão de feedback desativada: o backend admin está limitado a utilizadores.
http_response_code(403);
header('Location: painel_admin.php?msg=Gestao de feedback desativada');
exit;
?>




