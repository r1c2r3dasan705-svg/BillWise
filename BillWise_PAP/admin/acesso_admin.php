<?php
// Funcoes de controlo de acesso ao backend administrativo
require_once __DIR__ . '/../php/config.php';

// Email da conta com privilégios totais de administração.
define('ADMIN_EMAIL', 'supbillwise@gmail.com');

// Normaliza emails para comparações consistentes.
function normalizeEmail($email) {
    return strtolower(trim((string)$email));
}

// Obtém o email do utilizador atual, usando sessão e fallback à BD.
function currentUserEmail() {
    if (!empty($_SESSION['email'])) {
        return normalizeEmail($_SESSION['email']);
    }

    if (empty($_SESSION['user_id'])) {
        return null;
    }

    try {
        // Se o email não estiver em sessão, carrega-o da base de dados.
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT email FROM utilizadores WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch();

        if (!$row || empty($row['email'])) {
            return null;
        }

        $email = normalizeEmail($row['email']);
        $_SESSION['email'] = $email;
        $_SESSION['is_admin'] = ($email === ADMIN_EMAIL);

        return $email;
    } catch (Exception $e) {
        return null;
    }
}

// Determina se o utilizador autenticado é administrador.
function currentUserIsAdmin() {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    if (isset($_SESSION['is_admin'])) {
        return (bool)$_SESSION['is_admin'];
    }

    $email = currentUserEmail();
    return $email === ADMIN_EMAIL;
}

// Protege páginas admin: exige login e perfil de administrador.
function requireAdminPageAccess() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    if (!currentUserIsAdmin()) {
        http_response_code(403);
        echo 'Acesso negado';
        exit;
    }
}

// Protege ações admin (API/form): responde 403 quando não autorizado.
function requireAdminApiAccess() {
    if (!isset($_SESSION['user_id']) || !currentUserIsAdmin()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Acesso negado']);
        exit;
    }
}

// Gera/reutiliza token CSRF da área de administração.
function adminCsrfToken() {
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf_token'];
}

// Valida o token CSRF recebido no pedido.
function validateAdminCsrf($token) {
    return !empty($_SESSION['admin_csrf_token']) && hash_equals($_SESSION['admin_csrf_token'], (string)$token);
}
