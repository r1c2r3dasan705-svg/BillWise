<?php
/*
|--------------------------------------------------------------------------
| Criação de tópico
|--------------------------------------------------------------------------
| Recebe os dados do formulário, valida o conteúdo mínimo e grava um novo
| tópico associado ao utilizador autenticado.
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
$titulo = trim($dados['titulo'] ?? '');
$conteudo = trim($dados['conteudo'] ?? '');

if (mb_strlen($titulo) < 5 || mb_strlen($conteudo) < 10) {
    echo json_encode(['success' => false, 'error' => 'Título e conteúdo são obrigatórios']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('INSERT INTO topicos (utilizador_id, titulo, conteudo) VALUES (?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], $titulo, $conteudo]);

    $topicoId = (int) $pdo->lastInsertId();

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Tópico criado com sucesso',
        'topico' => [
            'id' => $topicoId,
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'autor_nome' => $_SESSION['name'] ?? 'Utilizador',
            'criado_em' => date('d/m/Y H:i'),
            'respostas_count' => 0,
        ],
    ]);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('Erro ao adicionar tópico: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Erro ao criar tópico']);
}
?>
