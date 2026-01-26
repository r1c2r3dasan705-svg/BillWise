<?php
// API para eliminar orçamento - verifica permissões antes de apagar
header('Content-Type: application/json');
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

// Receber dados JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$budget_id = $data['budget_id'] ?? 0;

// Validar ID do orçamento
if (!$budget_id) {
    echo json_encode(['success' => false, 'error' => 'Orçamento inválido']);
    exit;
}

try {
    require_once 'config.php';
    
    $user_id = $_SESSION['user_id'];
    
    // Verificar se o orçamento pertence ao utilizador autenticado (segurança)
    $stmt = $pdo->prepare("SELECT utilizador_id FROM orcamentos WHERE id = ?");
    $stmt->execute([$budget_id]);
    $budget = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Impedir eliminação de orçamentos de outros utilizadores
    if (!$budget || $budget['utilizador_id'] != $user_id) {
        echo json_encode(['success' => false, 'error' => 'Não tem permissão para eliminar este orçamento']);
        exit;
    }
    
    // Eliminar orçamento da base de dados
    $stmt = $pdo->prepare("DELETE FROM orcamentos WHERE id = ?");
    $stmt->execute([$budget_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Orçamento eliminado com sucesso'
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao eliminar orçamento: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao eliminar orçamento'
    ]);
}
