<?php
// API para atualizar dados de perfil do utilizador (nome e email)
header('Content-Type: application/json; charset=UTF-8');
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
$nome = trim($data['nome'] ?? '');
$email = trim($data['email'] ?? '');

// Validar campos obrigatórios
if (empty($nome) || empty($email)) {
    echo json_encode(['success' => false, 'error' => 'Preencha todos os campos']);
    exit;
}

// Validar formato de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Email inválido']);
    exit;
}

try {
    require_once 'config.php';
    
    // Verificar se email já está em uso por outro utilizador (evitar duplicados)
    $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Este email já está em uso']);
        exit;
    }
    
    // Atualizar nome e email na base de dados
    $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, email = ? WHERE id = ?");
    $stmt->execute([$nome, $email, $user_id]);
    
    // Atualizar nome na sessão para refletir mudanças imediatamente
    $_SESSION['name'] = $nome;
    
    echo json_encode([
        'success' => true,
        'message' => 'Perfil atualizado com sucesso'
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao atualizar perfil: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao atualizar perfil. Tente novamente.'
    ]);
}


