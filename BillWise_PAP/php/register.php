<?php
// API de registo - cria nova conta de utilizador e inicia sessão automaticamente
header('Content-Type: application/json');
require_once 'config.php';

// Receber dados JSON do cliente
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// Extrair e validar campos obrigatórios
$name = trim($data['nome'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['senha'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios em falta']);
    exit;
}

try {
    $pdo = getPDO();
    // Verificar se email já está registado (evitar duplicados)
    $stmt = $pdo->prepare('SELECT id FROM utilizadores WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email já em uso']);
        exit;
    }

    // Criar hash seguro da senha e inserir novo utilizador
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO utilizadores (nome, email, senha_hash, criado_em) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$name, $email, $hash]);

    // Iniciar sessão automaticamente após registo bem-sucedido
    $userId = $pdo->lastInsertId();
    if ($userId) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['name'] = $name;
    }

    echo json_encode(['success' => true, 'name' => $name]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
