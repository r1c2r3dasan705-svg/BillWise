<?php
/*
|--------------------------------------------------------------------------
| Listagem de tópicos do fórum
|--------------------------------------------------------------------------
| Devolve os tópicos mais recentes e indica se o utilizador autenticado
| pode editar ou apagar cada registo.
*/

session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

require_once 'configuracao.php';
require_once __DIR__ . '/../admin/acesso_admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

$utilizadorIdAtual = (int) $_SESSION['user_id'];
$isAdmin = currentUserIsAdmin();

try {
    $stmt = $pdo->prepare(
        "
        SELECT
            t.id,
            t.utilizador_id,
            t.titulo,
            t.conteudo,
            t.respostas_count,
            t.criado_em,
            u.nome AS autor_nome
        FROM topicos t
        INNER JOIN utilizadores u ON u.id = t.utilizador_id
        ORDER BY t.criado_em DESC, t.id DESC
        LIMIT 20
        "
    );
    $stmt->execute();

    $topicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($topicos as &$topico) {
        $autorId = (int) $topico['utilizador_id'];
        $topico['respostas_count'] = (int) ($topico['respostas_count'] ?? 0);
        $topico['criado_em'] = date('d/m/Y H:i', strtotime($topico['criado_em']));
        $topico['pode_editar'] = ($autorId === $utilizadorIdAtual);
        $topico['pode_apagar'] = ($autorId === $utilizadorIdAtual) || $isAdmin;
        unset($topico['utilizador_id']);
    }

    echo json_encode(['success' => true, 'topicos' => $topicos]);
} catch (PDOException $e) {
    error_log('Erro ao obter tópicos: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao carregar tópicos']);
}
?>

