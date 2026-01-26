<?php
// API para editar despesa existente - verifica propriedade antes de permitir edição
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

$expense_id = $data['id'] ?? 0;
$valor = $data['valor'] ?? 0;
$categoria = $data['categoria'] ?? '';
$data_despesa = $data['data'] ?? '';
$descricao = $data['descricao'] ?? '';

// Validar campos obrigatórios
if (!$expense_id || !$valor || !$categoria || !$data_despesa) {
    echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
    exit;
}

try {
    require_once 'config.php';
    
    $user_id = $_SESSION['user_id'];
    
    // Verificar se a despesa pertence ao utilizador autenticado (segurança)
    $stmt = $pdo->prepare("SELECT utilizador_id, valor, categoria FROM despesas WHERE id = ?");
    $stmt->execute([$expense_id]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Impedir edição de despesas de outros utilizadores
    if (!$expense || $expense['utilizador_id'] != $user_id) {
        echo json_encode(['success' => false, 'error' => 'Não tem permissão para editar esta despesa']);
        exit;
    }
    
    // Atualizar dados da despesa
    $stmt = $pdo->prepare("
        UPDATE despesas 
        SET valor = ?, categoria = ?, data = ?, descricao = ?
        WHERE id = ? AND utilizador_id = ?
    ");
    $stmt->execute([$valor, $categoria, $data_despesa, $descricao, $expense_id, $user_id]);

    // Atualizar gasto nos orÃ§amentos afetados
    if ($expense['categoria'] === $categoria) {
        $delta = $valor - $expense['valor'];
        if ($delta != 0) {
            $stmt = $pdo->prepare('UPDATE orcamentos SET gasto = GREATEST(0, gasto + ?) WHERE utilizador_id = ? AND nome = ?');
            $stmt->execute([$delta, $user_id, $categoria]);
        }
    } else {
        $stmt = $pdo->prepare('UPDATE orcamentos SET gasto = GREATEST(0, gasto - ?) WHERE utilizador_id = ? AND nome = ?');
        $stmt->execute([$expense['valor'], $user_id, $expense['categoria']]);

        $stmt = $pdo->prepare('UPDATE orcamentos SET gasto = GREATEST(0, gasto + ?) WHERE utilizador_id = ? AND nome = ?');
        $stmt->execute([$valor, $user_id, $categoria]);
    }
    
    // Buscar despesa atualizada para retornar ao cliente
    $stmt = $pdo->prepare("SELECT * FROM despesas WHERE id = ?");
    $stmt->execute([$expense_id]);
    $updated_expense = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'message' => 'Despesa atualizada com sucesso',
        'item' => $updated_expense
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao atualizar despesa: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao atualizar despesa'
    ]);
}
