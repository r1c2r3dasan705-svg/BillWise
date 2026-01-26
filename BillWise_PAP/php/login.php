<?php
// API de autenticação - verifica credenciais e inicia sessão do utilizador
header('Content-Type: application/json');
require_once 'config.php';
session_start();

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
    $stmt = $pdo->prepare('SELECT id, nome, senha_hash FROM utilizadores WHERE email = ?');
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

    echo json_encode(['success' => true, 'name' => $user['nome']]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>