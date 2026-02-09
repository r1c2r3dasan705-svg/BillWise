<?php
// Funcoes de controlo de acesso ao backend administrativo
require_once __DIR__ . '/../config.php';

define('ADMIN_EMAIL', 'supbillwise@gmail.com');

function normalizeEmail($email) {
    return strtolower(trim((string)$email));
}

function currentUserEmail() {
    if (!empty($_SESSION['email'])) {
        return normalizeEmail($_SESSION['email']);
    }

    if (empty($_SESSION['user_id'])) {
        return null;
    }

    try {
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

function requireAdminApiAccess() {
    if (!isset($_SESSION['user_id']) || !currentUserIsAdmin()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Acesso negado']);
        exit;
    }
}

function adminCsrfToken() {
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf_token'];
}

function validateAdminCsrf($token) {
    return !empty($_SESSION['admin_csrf_token']) && hash_equals($_SESSION['admin_csrf_token'], (string)$token);
}
?>

