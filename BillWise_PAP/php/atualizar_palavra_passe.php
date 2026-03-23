<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);

if (!is_array($payload)) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

$senhaAtual = $payload['senha_atual'] ?? '';
$novaSenha = $payload['nova_senha'] ?? '';

if ($senhaAtual === '' || $novaSenha === '') {
    echo json_encode(['success' => false, 'error' => 'Preencha todos os campos']);
    exit;
}

if (strlen($novaSenha) < 6) {
    echo json_encode(['success' => false, 'error' => 'A nova password deve ter pelo menos 6 caracteres']);
    exit;
}

try {
    require_once 'configuracao.php';

    $stmt = $pdo->prepare('SELECT senha_hash FROM utilizadores WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($senhaAtual, $user['senha_hash'])) {
        echo json_encode(['success' => false, 'error' => 'A password atual está incorreta']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE utilizadores SET senha_hash = ? WHERE id = ?');
    $stmt->execute([password_hash($novaSenha, PASSWORD_DEFAULT), $_SESSION['user_id']]);

    echo json_encode(['success' => true, 'message' => 'Password atualizada com sucesso']);
} catch (PDOException $e) {
    error_log('Erro ao atualizar password: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erro ao atualizar password. Tente novamente.']);
}
?>

