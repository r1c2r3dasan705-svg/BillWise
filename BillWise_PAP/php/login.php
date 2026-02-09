<?php
// API de autenticaÃ§Ã£o - verifica credenciais e inicia sessÃ£o do utilizador
header('Content-Type: application/json');
require_once 'config.php';
require_once 'admin/admin_access.php';
session_start();

// Receber dados JSON do cliente
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados invÃ¡lidos']);
    exit;
}

// Extrair e validar campos obrigatÃ³rios
$email = trim($data['email'] ?? '');
$password = $data['senha'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatÃ³rios em falta']);
    exit;
}

try {
    $pdo = getPDO();
    // Procurar utilizador por email
    $stmt = $pdo->prepare('SELECT id, nome, email, senha_hash FROM utilizadores WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verificar se utilizador existe e senha estÃ¡ correta (comparaÃ§Ã£o segura com hash)
    if (!$user || !password_verify($password, $user['senha_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Email ou senha invÃ¡lidos']);
        exit;
    }

    // AutenticaÃ§Ã£o bem-sucedida - criar sessÃ£o
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['nome'];
    $_SESSION['email'] = normalizeEmail($user['email']);
    $_SESSION['is_admin'] = ($_SESSION['email'] === ADMIN_EMAIL);

    echo json_encode(['success' => true, 'name' => $user['nome']]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>

