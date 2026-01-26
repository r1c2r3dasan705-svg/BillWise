<?php
// API para alterar senha do utilizador autenticado
header('Content-Type: application/json');
session_start();

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

// Receber dados JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

$user_id = $_SESSION['user_id'];
$senha_atual = $data['senha_atual'] ?? '';
$nova_senha = $data['nova_senha'] ?? '';

// Validar campos obrigatórios
if (empty($senha_atual) || empty($nova_senha)) {
    echo json_encode(['success' => false, 'error' => 'Preencha todos os campos']);
    exit;
}

// Validar requisitos de segurança
if (strlen($nova_senha) < 6) {
    echo json_encode(['success' => false, 'error' => 'A nova senha deve ter pelo menos 6 caracteres']);
    exit;
}

try {
    require_once 'config.php';
    
    // Buscar senha atual da base de dados
    $stmt = $pdo->prepare("SELECT senha FROM utilizadores WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verificar se senha atual está correta (segurança: utilizador deve conhecer senha atual)
    if (!$user || !password_verify($senha_atual, $user['senha'])) {
        echo json_encode(['success' => false, 'error' => 'Senha atual incorreta']);
        exit;
    }
    
    // Criar hash seguro da nova senha e atualizar na base de dados
    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE utilizadores SET senha = ? WHERE id = ?");
    $stmt->execute([$nova_senha_hash, $user_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Senha alterada com sucesso'
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao alterar senha: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao alterar senha. Tente novamente.'
    ]);
}
