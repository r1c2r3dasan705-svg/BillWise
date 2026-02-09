<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

require_once 'admin_access.php';
requireAdminApiAccess();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../admin/backend.php?msg=Metodo invalido');
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validateAdminCsrf($csrf_token)) {
    header('Location: ../../admin/backend.php?msg=Token invalido');
    exit;
}

$action = trim($_POST['action'] ?? '');
$status = trim($_POST['status'] ?? '');
$selected_ids = $_POST['feedback_ids'] ?? [];
$allowed_status = ['pendente', 'em_analise', 'resolvido'];

try {
    $pdo = getPDO();

    if ($action === 'update_one') {
        $feedback_id = (int)($_POST['feedback_id'] ?? 0);
        if ($feedback_id <= 0 || !in_array($status, $allowed_status, true)) {
            header('Location: ../../admin/backend.php?msg=Dados invalidos');
            exit;
        }

        $stmt = $pdo->prepare('UPDATE feedback SET status = ? WHERE id = ?');
        $stmt->execute([$status, $feedback_id]);
        header('Location: ../../admin/backend.php?msg=Feedback atualizado');
        exit;
    }

    if ($action === 'bulk_update_selected') {
        if (!in_array($status, $allowed_status, true)) {
            header('Location: ../../admin/backend.php?msg=Estado invalido');
            exit;
        }

        $ids = array_map('intval', is_array($selected_ids) ? $selected_ids : []);
        $ids = array_values(array_filter($ids, fn($id) => $id > 0));
        if (empty($ids)) {
            header('Location: ../../admin/backend.php?msg=Selecione pelo menos um feedback');
            exit;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $params = array_merge([$status], $ids);

        $stmt = $pdo->prepare("UPDATE feedback SET status = ? WHERE id IN ($placeholders)");
        $stmt->execute($params);
        header('Location: ../../admin/backend.php?msg=Feedback selecionado atualizado');
        exit;
    }

    if ($action === 'bulk_update_all') {
        if (!in_array($status, $allowed_status, true)) {
            header('Location: ../../admin/backend.php?msg=Estado invalido');
            exit;
        }

        $stmt = $pdo->prepare('UPDATE feedback SET status = ?');
        $stmt->execute([$status]);
        header('Location: ../../admin/backend.php?msg=Todos os feedback foram atualizados');
        exit;
    }

    if ($action === 'delete_selected') {
        $ids = array_map('intval', is_array($selected_ids) ? $selected_ids : []);
        $ids = array_values(array_filter($ids, fn($id) => $id > 0));
        if (empty($ids)) {
            header('Location: ../../admin/backend.php?msg=Selecione pelo menos um feedback');
            exit;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("DELETE FROM feedback WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        header('Location: ../../admin/backend.php?msg=Feedback selecionado removido');
        exit;
    }

    if ($action === 'delete_resolved') {
        $stmt = $pdo->prepare("DELETE FROM feedback WHERE status = 'resolvido'");
        $stmt->execute();
        header('Location: ../../admin/backend.php?msg=Feedback resolvido removido');
        exit;
    }

    header('Location: ../../admin/backend.php?msg=Acao invalida');
    exit;
} catch (Exception $e) {
    header('Location: ../../admin/backend.php?msg=Erro ao gerir feedback');
    exit;
}
?>


