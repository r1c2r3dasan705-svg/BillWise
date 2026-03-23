<?php
/*
|--------------------------------------------------------------------------
| Edição de tópico do fórum
|--------------------------------------------------------------------------
| Permite que o autor do tópico atualize o título e o conteúdo do seu
| próprio tópico.
*/

session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

require_once 'configuracao.php';

$dados = json_decode(file_get_contents('php://input'), true);
$topicoId = (int) ($dados['topico_id'] ?? 0);
$titulo = trim($dados['titulo'] ?? '');
$conteudo = trim($dados['conteudo'] ?? '');
$utilizadorIdAtual = (int) $_SESSION['user_id'];

if ($topicoId <= 0 || mb_strlen($titulo) < 5 || mb_strlen($conteudo) < 10) {
    echo json_encode(['success' => false, 'error' => 'Dados do tópico inválidos']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT utilizador_id FROM topicos WHERE id = ? LIMIT 1');
    $stmt->execute([$topicoId]);
    $topico = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$topico) {
        echo json_encode(['success' => false, 'error' => 'Tópico não encontrado']);
        exit;
    }

    if ((int) $topico['utilizador_id'] !== $utilizadorIdAtual) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Não pode editar este tópico']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE topicos SET titulo = ?, conteudo = ? WHERE id = ?');
    $stmt->execute([$titulo, $conteudo, $topicoId]);

    echo json_encode([
        'success' => true,
        'message' => 'Tópico atualizado com sucesso',
        'topico' => [
            'id' => $topicoId,
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'autor_nome' => $_SESSION['name'] ?? 'Utilizador',
        ],
    ]);
} catch (PDOException $e) {
    error_log('Erro ao editar tópico: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao atualizar tópico']);
}
?>

