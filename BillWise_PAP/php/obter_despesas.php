<?php
// API para procurar todas as despesas do utilizador autenticado - ordenadas por data
header('Content-Type: application/json; charset=UTF-8');
require_once 'configuracao.php';
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

try {
    $pdo = getPDO();
    
    // Obter período solicitado (em meses)
    $period = isset($_GET['period']) ? intval($_GET['period']) : 1;
    
    // Calcular data inicial baseado no período
    if ($period === 1) {
        // Este mês
        $startDate = date('Y-m-01');
    } elseif ($period === 3) {
        // Últimos 3 meses
        $startDate = date('Y-m-d', strtotime('-3 months'));
    } elseif ($period === 12) {
        // Este ano
        $startDate = date('Y-01-01');
    } else {
        $startDate = date('Y-m-01');
    }
    
    // Procurar despesas do utilizador ordenadas por data (mais recentes primeiro)
    $stmt = $pdo->prepare('SELECT id, valor, categoria, data, descricao, criado_em FROM despesas WHERE utilizador_id = ? AND data >= ? ORDER BY data DESC, id DESC');
    $stmt->execute([$_SESSION['user_id'], $startDate]);
    $expenses = $stmt->fetchAll();
    echo json_encode(['success' => true, 'items' => $expenses]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>


