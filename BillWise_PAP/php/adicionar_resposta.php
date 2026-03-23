<?php
/*
|--------------------------------------------------------------------------
| Criação de resposta
|--------------------------------------------------------------------------
| Adiciona uma resposta a um tópico existente e atualiza o contador de
| respostas guardado no registo do tópico.
*/

session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

require_once 'configuracao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
$topicoId = (int) ($dados['topico_id'] ?? 0);
$conteudo = trim($dados['conteudo'] ?? '');

if ($topicoId <= 0 || mb_strlen($conteudo) < 3) {
    echo json_encode(['success' => false, 'error' => 'Tópico inválido ou resposta demasiado curta']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('INSERT INTO respostas (topico_id, utilizador_id, conteudo) VALUES (?, ?, ?)');
    $stmt->execute([$topicoId, $_SESSION['user_id'], $conteudo]);

    $stmt = $pdo->prepare('UPDATE topicos SET respostas_count = respostas_count + 1 WHERE id = ?');
    $stmt->execute([$topicoId]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Resposta adicionada']);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Erro ao adicionar resposta: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erro ao adicionar resposta']);
}
?>
