<?php
// API para criar novo orçamento
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

// Receber dados do orçamento
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$name = trim($data['nome'] ?? '');
$limit = floatval($data['limite'] ?? 0);

if (!$name || !$limit) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios em falta']);
    exit;
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('INSERT INTO orcamentos (utilizador_id, nome, limite, gasto, criado_em) VALUES (?, ?, ?, 0, NOW())');
    $stmt->execute([$_SESSION['user_id'], $name, $limit]);
    $id = $pdo->lastInsertId();
    
    $response = [
        'success' => true,
        'item' => ['id' => $id, 'nome' => $name, 'limite' => $limit, 'gasto' => 0]
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>


