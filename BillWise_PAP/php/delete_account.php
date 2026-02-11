<?php
// API para eliminar conta do utilizador e todos os dados associados
header('Content-Type: application/json; charset=UTF-8');
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    require_once 'config.php';
    
    // Iniciar transação para garantir eliminação completa ou rollback em caso de erro
    $pdo->beginTransaction();
    
    // Eliminar feedback submetido pelo utilizador
    $stmt = $pdo->prepare("DELETE FROM feedback WHERE utilizador_id = ?");
    $stmt->execute([$user_id]);
    
    // Eliminar orçamentos criados pelo utilizador
    $stmt = $pdo->prepare("DELETE FROM orcamentos WHERE utilizador_id = ?");
    $stmt->execute([$user_id]);
    
    // Eliminar despesas registadas pelo utilizador
    $stmt = $pdo->prepare("DELETE FROM despesas WHERE utilizador_id = ?");
    $stmt->execute([$user_id]);
    
    // Eliminar registo do utilizador
    $stmt = $pdo->prepare("DELETE FROM utilizadores WHERE id = ?");
    $stmt->execute([$user_id]);
    
    // Confirmar todas as eliminações
    $pdo->commit();
    
    // Destruir sessão do utilizador
    session_destroy();
    
    echo json_encode([
        'success' => true,
        'message' => 'Conta eliminada com sucesso'
    ]);
    
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Erro ao eliminar conta: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao eliminar conta. Tente novamente.'
    ]);
}


