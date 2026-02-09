<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

require_once 'admin_access.php';
requireAdminApiAccess();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../admin/backend.php?msg=Metodo invalido');
    exit;
}

$user_id = (int)($_POST['user_id'] ?? 0);
$csrf_token = $_POST['csrf_token'] ?? '';

if ($user_id <= 0) {
    header('Location: ../../admin/backend.php?msg=Utilizador invalido');
    exit;
}

if (!validateAdminCsrf($csrf_token)) {
    header('Location: ../../admin/backend.php?msg=Token invalido');
    exit;
}

if (!empty($_SESSION['user_id']) && $user_id === (int)$_SESSION['user_id']) {
    header('Location: ../../admin/backend.php?msg=Nao pode apagar a sua propria conta admin');
    exit;
}

try {
    $pdo = getPDO();

    $stmt = $pdo->prepare('SELECT email FROM utilizadores WHERE id = ? LIMIT 1');
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();

    if (!$row) {
        header('Location: ../../admin/backend.php?msg=Utilizador nao encontrado');
        exit;
    }

    if (normalizeEmail($row['email']) === ADMIN_EMAIL) {
        header('Location: ../../admin/backend.php?msg=Nao e permitido apagar o admin principal');
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM utilizadores WHERE id = ?');
    $stmt->execute([$user_id]);

    header('Location: ../../admin/backend.php?msg=Utilizador apagado com sucesso');
    exit;
} catch (Exception $e) {
    header('Location: ../../admin/backend.php?msg=Erro ao apagar utilizador');
    exit;
}
?>


