<?php
// API para buscar todas as despesas do utilizador autenticado - ordenadas por data
header('Content-Type: application/json');
require_once 'config.php';
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

try {
    $pdo = getPDO();
    // Buscar despesas do utilizador ordenadas por data (mais recentes primeiro)
    $stmt = $pdo->prepare('SELECT id, valor, categoria, data, descricao, criado_em FROM despesas WHERE utilizador_id = ? ORDER BY data DESC, id DESC');
    $stmt->execute([$_SESSION['user_id']]);
    $expenses = $stmt->fetchAll();
    echo json_encode(['success' => true, 'items' => $expenses]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>