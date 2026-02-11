<?php
// API para adicionar nova despesa
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';
session_start();

// Verificar se o utilizador está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

// Receber e decodificar dados JSON do pedido
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

 $amount = floatval($data['valor'] ?? 0);
$category = trim($data['categoria'] ?? '');
date_default_timezone_set('Europe/Lisbon');
$date = $data['data'] ?? date('Y-m-d');
$description = trim($data['descricao'] ?? '');

if (!$amount || !$category) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios em falta']);
    exit;
}

try {
    $pdo = getPDO();
    $stmt = $pdo->prepare('INSERT INTO despesas (utilizador_id, valor, categoria, data, descricao, criado_em) VALUES (?, ?, ?, ?, ?, NOW())');
    $stmt->execute([$_SESSION['user_id'], $amount, $category, $date, $description]);
    $id = $pdo->lastInsertId();

    // Atualizar gasto do orçamento da mesma categoria, se existir
    $stmt = $pdo->prepare('UPDATE orcamentos SET gasto = GREATEST(0, gasto + ?) WHERE utilizador_id = ? AND nome = ?');
    $stmt->execute([$amount, $_SESSION['user_id'], $category]);

    $response = [
        'success' => true, 
        'item' => [
            'id' => $id, 
            'valor' => $amount, 
            'categoria' => $category, 
            'data' => $date, 
            'descricao' => $description
        ]
    ];
    
    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>



