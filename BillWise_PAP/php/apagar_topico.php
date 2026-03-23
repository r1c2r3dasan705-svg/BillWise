<?php
/*
|--------------------------------------------------------------------------
| Remoção de tópico do fórum
|--------------------------------------------------------------------------
| O autor pode apagar o seu próprio tópico e o administrador pode apagar
| qualquer tópico.
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
require_once __DIR__ . '/../admin/acesso_admin.php';

$dados = json_decode(file_get_contents('php://input'), true);
$topicoId = (int) ($dados['topico_id'] ?? 0);
$utilizadorIdAtual = (int) $_SESSION['user_id'];
$isAdmin = currentUserIsAdmin();

if ($topicoId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Tópico inválido']);
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

    $isOwner = ((int) $topico['utilizador_id'] === $utilizadorIdAtual);

    if (!$isOwner && !$isAdmin) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Não pode apagar este tópico']);
        exit;
    }

    $stmt = $pdo->prepare('DELETE FROM topicos WHERE id = ?');
    $stmt->execute([$topicoId]);

    echo json_encode(['success' => true, 'message' => 'Tópico apagado com sucesso']);
} catch (PDOException $e) {
    error_log('Erro ao apagar tópico: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao apagar tópico']);
}
?>

