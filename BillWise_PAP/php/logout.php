<?php
// Terminar sessão do utilizador e redirecionar para página inicial
session_start();
// Limpar todas as variáveis de sessão
$_SESSION = [];
// Eliminar cookie de sessão do navegador
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
// Destruir sessão no servidor
session_destroy();
header('Location: ../index.php');
exit;
?>
