<?php
/*
|--------------------------------------------------------------------------
| Entrada de utilizador
|--------------------------------------------------------------------------
| Valida as credenciais recebidas, inicia a sessão e marca o utilizador
| como administrador quando o email corresponde à conta de gestão.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=UTF-8');

require_once 'configuracao.php';
require_once __DIR__ . '/../admin/acesso_admin.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (!is_array($dados)) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$email = trim($dados['email'] ?? '');
$palavraPasse = $dados['senha'] ?? '';

if ($email === '' || $palavraPasse === '') {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios em falta']);
    exit;
}

try {
    $pdo = getPDO();

    $stmt = $pdo->prepare('SELECT id, nome, email, senha_hash FROM utilizadores WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($palavraPasse, $user['senha_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Email ou palavra-passe inválidos']);
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['nome'];
    $_SESSION['email'] = normalizeEmail($user['email']);
    $_SESSION['is_admin'] = ($_SESSION['email'] === ADMIN_EMAIL);

    echo json_encode(['success' => true, 'name' => $user['nome']]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno no servidor']);
}
?>
