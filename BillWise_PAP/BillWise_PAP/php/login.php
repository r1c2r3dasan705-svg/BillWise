<?php
// API de autenticação - verifica credenciais e inicia sessão do utilizador
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';
require_once __DIR__ . '/../admin/acesso_admin.php';

// Receber dados JSON do cliente
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// Extrair e validar campos obrigatórios
$email = trim($data['email'] ?? '');
$password = $data['senha'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios em falta']);
    exit;
}

try {
    $pdo = getPDO();
    // Procurar utilizador por email
    $stmt = $pdo->prepare('SELECT id, nome, email, senha_hash FROM utilizadores WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verificar se utilizador existe e senha está correta (comparação segura com hash)
    if (!$user || !password_verify($password, $user['senha_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Email ou senha inválidos']);
        exit;
    }

    // Autenticação bem-sucedida - criar sessão
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['nome'];
    $_SESSION['email'] = normalizeEmail($user['email']);
    $_SESSION['is_admin'] = ($_SESSION['email'] === ADMIN_EMAIL);

    echo json_encode(['success' => true, 'name' => $user['nome']]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}


