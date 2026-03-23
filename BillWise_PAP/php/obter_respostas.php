<?php
/*
|--------------------------------------------------------------------------
| Listagem de respostas de um tópico
|--------------------------------------------------------------------------
| Devolve as respostas associadas a um tópico específico, ordenadas por data
| de criação, para serem mostradas por baixo do cartão do tópico.
*/

session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

require_once 'configuracao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

$topicoId = (int) ($_GET['topico_id'] ?? 0);

if ($topicoId <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Tópico inválido']);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "
        SELECT
            r.id,
            r.conteudo,
            r.criado_em,
            u.nome AS autor_nome
        FROM respostas r
        INNER JOIN utilizadores u ON u.id = r.utilizador_id
        WHERE r.topico_id = ?
        ORDER BY r.criado_em ASC, r.id ASC
        "
    );
    $stmt->execute([$topicoId]);

    $respostas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($respostas as &$resposta) {
        $resposta['id'] = (int) $resposta['id'];
        $resposta['criado_em'] = date('d/m/Y H:i', strtotime($resposta['criado_em']));
    }

    echo json_encode(['success' => true, 'respostas' => $respostas]);
} catch (PDOException $e) {
    error_log('Erro ao obter respostas: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao carregar respostas']);
}
?>
