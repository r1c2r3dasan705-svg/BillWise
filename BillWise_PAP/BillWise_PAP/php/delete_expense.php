<?php
// API para eliminar despesa - verifica permissões antes de apagar
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

$expense_id = $data['expense_id'] ?? 0;

// Validar ID da despesa
if (!$expense_id) {
    echo json_encode(['success' => false, 'error' => 'Despesa inválida']);
    exit;
}

try {
    require_once 'config.php';
    
    $user_id = $_SESSION['user_id'];
    
    // Verificar se a despesa pertence ao utilizador autenticado (segurança)
    $stmt = $pdo->prepare("SELECT utilizador_id, valor, categoria FROM despesas WHERE id = ?");
    $stmt->execute([$expense_id]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Impedir eliminação de despesas de outros utilizadores
    if (!$expense || $expense['utilizador_id'] != $user_id) {
        echo json_encode(['success' => false, 'error' => 'Não tem permissão para eliminar esta despesa']);
        exit;
    }
    
    // Eliminar despesa da base de dados
    $stmt = $pdo->prepare("DELETE FROM despesas WHERE id = ?");
    $stmt->execute([$expense_id]);

    // Atualizar gasto do orçamento da mesma categoria, se existir
    $stmt = $pdo->prepare('UPDATE orcamentos SET gasto = GREATEST(0, gasto - ?) WHERE utilizador_id = ? AND nome = ?');
    $stmt->execute([$expense['valor'], $user_id, $expense['categoria']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Despesa eliminada com sucesso'
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao eliminar despesa: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao eliminar despesa'
    ]);
}



