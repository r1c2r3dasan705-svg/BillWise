<?php
// API para editar orçamento - atualiza nome e limite mantendo gasto atual
header('Content-Type: application/json; charset=UTF-8');
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

// Receber dados JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$budget_id = $data['id'] ?? 0;
$nome = $data['nome'] ?? '';
$limite = $data['limite'] ?? 0;

// Validar campos obrigatórios
if (!$budget_id || !$nome || !$limite) {
    echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
    exit;
}

try {
    require_once 'config.php';
    
    $user_id = $_SESSION['user_id'];
    
    // Verificar se o orçamento pertence ao utilizador autenticado (segurança)
    $stmt = $pdo->prepare("SELECT utilizador_id FROM orcamentos WHERE id = ?");
    $stmt->execute([$budget_id]);
    $budget = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Impedir edição de orçamentos de outros utilizadores
    if (!$budget || $budget['utilizador_id'] != $user_id) {
        echo json_encode(['success' => false, 'error' => 'Não tem permissão para editar este orçamento']);
        exit;
    }
    
    // Atualizar orçamento (mantém o gasto atual, apenas altera nome e limite)
    $stmt = $pdo->prepare("
        UPDATE orcamentos 
        SET nome = ?, limite = ?
        WHERE id = ? AND utilizador_id = ?
    ");
    $result = $stmt->execute([$nome, $limite, $budget_id, $user_id]);
    
    if (!$result) {
        echo json_encode(['success' => false, 'error' => 'Erro ao atualizar orçamento']);
        exit;
    }
    
    // Buscar orçamento atualizado para retornar ao cliente
    $stmt = $pdo->prepare("SELECT * FROM orcamentos WHERE id = ?");
    $stmt->execute([$budget_id]);
    $updated_budget = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'message' => 'Orçamento atualizado com sucesso',
        'item' => $updated_budget
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao atualizar orçamento: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao atualizar orçamento'
    ]);
}


