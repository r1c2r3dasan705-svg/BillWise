<?php
// API para buscar todos os orçamentos do utilizador autenticado
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
    // Buscar orçamentos do utilizador com gasto calculado no mês atual
    $stmt = $pdo->prepare("
        SELECT 
            o.id,
            o.nome,
            o.limite,
            o.criado_em,
            COALESCE((
                SELECT SUM(d.valor)
                FROM despesas d
                WHERE d.utilizador_id = o.utilizador_id
                  AND d.categoria = o.nome
                  AND MONTH(d.data) = MONTH(CURRENT_DATE())
                  AND YEAR(d.data) = YEAR(CURRENT_DATE())
            ), 0) AS gasto
        FROM orcamentos o
        WHERE o.utilizador_id = ?
        ORDER BY o.criado_em DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $items = $stmt->fetchAll();
    echo json_encode(['success' => true, 'items' => $items]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>

