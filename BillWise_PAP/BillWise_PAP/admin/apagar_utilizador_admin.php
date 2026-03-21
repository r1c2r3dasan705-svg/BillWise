<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

require_once 'acesso_admin.php';
requireAdminApiAccess();

// Aceita apenas submissões POST para evitar execuções acidentais.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: painel_admin.php?msg=Metodo invalido');
    exit;
}

$user_id = (int)($_POST['user_id'] ?? 0);
$csrf_token = $_POST['csrf_token'] ?? '';

// Garante que existe um ID de utilizador válido.
if ($user_id <= 0) {
    header('Location: painel_admin.php?msg=Utilizador invalido');
    exit;
}

// Bloqueia pedidos sem token CSRF válido.
if (!validateAdminCsrf($csrf_token)) {
    header('Location: painel_admin.php?msg=Token invalido');
    exit;
}

// Impede o admin autenticado de apagar a própria conta.
if (!empty($_SESSION['user_id']) && $user_id === (int)$_SESSION['user_id']) {
    header('Location: painel_admin.php?msg=Nao pode apagar a sua propria conta admin');
    exit;
}

try {
    $pdo = getPDO();

    // Confirma primeiro se o utilizador existe.
    $stmt = $pdo->prepare('SELECT email FROM utilizadores WHERE id = ? LIMIT 1');
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();

    if (!$row) {
        header('Location: painel_admin.php?msg=Utilizador nao encontrado');
        exit;
    }

    // Protege a conta principal de administração.
    if (normalizeEmail($row['email']) === ADMIN_EMAIL) {
        header('Location: painel_admin.php?msg=Nao e permitido apagar o admin principal');
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM utilizadores WHERE id = ?');
    $stmt->execute([$user_id]);

    header('Location: painel_admin.php?msg=Utilizador apagado com sucesso');
    exit;
} catch (Exception $e) {
    header('Location: painel_admin.php?msg=Erro ao apagar utilizador');
    exit;
}
?>




