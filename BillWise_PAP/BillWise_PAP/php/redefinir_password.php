<?php
// API para redefinir password com código de recuperação
// O código é validado a partir da sessão (não da BD)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
// Funções SMTP (copiadas de enviar_email_suporte.php)
session_start();
require_once 'config.php';
$pdo = getPDO();
// Verificar se o utilizador já tem um código de recuperação válido na sessão
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
$codigo = trim($data['codigo'] ?? '');
$novaSenha = $data['nova_senha'] ?? '';

if (!$email || !$codigo || strlen($codigo) !== 6 || strlen($novaSenha) < 6) {
    error_log("Redefinir validation failed");
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

try {
    // Verificar se existe código na sessão
    error_log("Session codigo_recuperacao exists: " . (isset($_SESSION['codigo_recuperacao']) ? 'yes' : 'no'));
    if (!isset($_SESSION['codigo_recuperacao'])) {
        echo json_encode(['success' => false, 'error' => 'Código expirado ou inválido']);
        exit;
    }
    
    $sessao = $_SESSION['codigo_recuperacao'];
    
    // Verificar se o código ainda é válido (não expirou)
    if (time() > $sessao['expiracao']) {
        error_log("Code expired. Time: " . time() . ", expiry: " . $sessao['expiracao']);
        unset($_SESSION['codigo_recuperacao']);
        echo json_encode(['success' => false, 'error' => 'Código expirado. Peça um novo código.']);
        exit;
    }
    
    // Verificar se o código corresponde e o email é o mesmo
    error_log("Code match check: session=" . $sessao['codigo'] . ", input=" . $codigo . ", email match=" . ($sessao['email'] === $email ? 'yes' : 'no'));
    if ($sessao['codigo'] !== $codigo || $sessao['email'] !== $email) {
        echo json_encode(['success' => false, 'error' => 'Código inválido']);
        exit;
    }
    
    // Atualizar password do utilizador
    $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE utilizadores SET senha_hash = ? WHERE email = ?");
    $success = $stmt->execute([$senhaHash, $email]);
    if (!$success || $stmt->rowCount() === 0) {
        error_log("Password update failed for email: " . $email . " rowCount: " . $stmt->rowCount());
        echo json_encode(['success' => false, 'error' => 'Erro ao atualizar password. Utilizador não encontrado.']);
        exit;
    }
    
    // Limpar o código da sessão após uso
    unset($_SESSION['codigo_recuperacao']);
    
    error_log("Password reset successful for email: " . $email);
    echo json_encode(['success' => true, 'message' => 'Password alterada com sucesso']);

    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erro ao processar pedido: ' . $e->getMessage()]);
}

