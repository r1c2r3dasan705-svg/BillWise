<?php
/*
|--------------------------------------------------------------------------
| Registo de utilizador
|--------------------------------------------------------------------------
| Cria uma nova conta, grava a palavra-passe em hash seguro e inicia a
| sessão do utilizador assim que o registo é concluído.
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

$nome = trim($dados['nome'] ?? '');
$email = trim($dados['email'] ?? '');
$palavraPasse = $dados['senha'] ?? '';

if ($nome === '' || $email === '' || $palavraPasse === '') {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios em falta']);
    exit;
}

try {
    $pdo = getPDO();

    $stmt = $pdo->prepare('SELECT id FROM utilizadores WHERE email = ?');
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email já em uso']);
        exit;
    }

    $hash = password_hash($palavraPasse, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO utilizadores (nome, email, senha_hash, criado_em) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$nome, $email, $hash]);

    $userId = (int) $pdo->lastInsertId();

    if ($userId > 0) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['name'] = $nome;
        $_SESSION['email'] = normalizeEmail($email);
        $_SESSION['is_admin'] = ($_SESSION['email'] === ADMIN_EMAIL);
    }

    echo json_encode(['success' => true, 'name' => $nome]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno no servidor']);
}
?>
