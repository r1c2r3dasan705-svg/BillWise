<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

require_once 'admin_access.php';
requireAdminApiAccess();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../admin/backend.php?msg=MÃ©todo invÃ¡lido');
    exit;
}

$feedback_id = (int)($_POST['feedback_id'] ?? 0);
$status = trim($_POST['status'] ?? '');
$csrf_token = $_POST['csrf_token'] ?? '';
$allowed_status = ['pendente', 'em_analise', 'resolvido'];

if ($feedback_id <= 0 || !in_array($status, $allowed_status, true)) {
    header('Location: ../../admin/backend.php?msg=Dados invÃ¡lidos');
    exit;
}

if (!validateAdminCsrf($csrf_token)) {
    header('Location: ../../admin/backend.php?msg=Token de seguranÃ§a invÃ¡lido');
    exit;
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('UPDATE feedback SET status = ? WHERE id = ?');
    $stmt->execute([$status, $feedback_id]);
    header('Location: ../../admin/backend.php?msg=Estado atualizado com sucesso');
    exit;
} catch (Exception $e) {
    header('Location: ../../admin/backend.php?msg=Erro ao atualizar estado');
    exit;
}
?>


