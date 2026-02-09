<?php
// API de registo - cria nova conta de utilizador e inicia sessÃ£o automaticamente
header('Content-Type: application/json');
require_once 'config.php';
require_once 'admin/admin_access.php';

// Receber dados JSON do cliente
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados invÃ¡lidos']);
    exit;
}

// Extrair e validar campos obrigatÃ³rios
$name = trim($data['nome'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['senha'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatÃ³rios em falta']);
    exit;
}

try {
    $pdo = getPDO();
    // Verificar se email jÃ¡ estÃ¡ registado (evitar duplicados)
    $stmt = $pdo->prepare('SELECT id FROM utilizadores WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email jÃ¡ em uso']);
        exit;
    }

    // Criar hash seguro da senha e inserir novo utilizador
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO utilizadores (nome, email, senha_hash, criado_em) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$name, $email, $hash]);

    // Iniciar sessÃ£o automaticamente apÃ³s registo bem-sucedido
    $userId = $pdo->lastInsertId();
    if ($userId) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = normalizeEmail($email);
        $_SESSION['is_admin'] = ($_SESSION['email'] === ADMIN_EMAIL);
    }

    echo json_encode(['success' => true, 'name' => $name]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>

