<?php
// API para submeter feedback - guarda na BD e envia email para billwise5646@gmail.com
header('Content-Type: application/json');
session_start();

// Receber dados JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

// Extrair e limpar campos
$nome = trim($data['nome'] ?? '');
$email = trim($data['email'] ?? '');
$tipo = trim($data['tipo'] ?? 'sugestao');
$assunto = trim($data['assunto'] ?? '');
$mensagem = trim($data['mensagem'] ?? '');

// Validar campos obrigatórios
if (empty($nome) || empty($email) || empty($assunto) || empty($mensagem)) {
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
    
    // ID do utilizador (null se não autenticado)
    $utilizador_id = $_SESSION['user_id'] ?? null;
    
    // Inserir feedback na base de dados com status "pendente"
    $stmt = $pdo->prepare("
        INSERT INTO feedback (utilizador_id, nome, email, tipo, assunto, mensagem, status)
        VALUES (?, ?, ?, ?, ?, ?, 'pendente')
    ");
    
    $stmt->execute([$utilizador_id, $nome, $email, $tipo, $assunto, $mensagem]);
    
    // Preparar email para enviar ao suporte BillWise
    $to = 'billwise5646@gmail.com';
    $subject = '[' . strtoupper($tipo) . '] ' . $assunto;
    $message = '';
    $message .= 'Tipo: ' . ucfirst($tipo) . "\n";
    $message .= 'Nome: ' . $nome . "\n";
    $message .= 'Email: ' . $email . "\n";
    $message .= 'Assunto: ' . $assunto . "\n\n";
    $message .= "Mensagem:\n" . $mensagem . "\n\n";
    $message .= "---\n";
    $message .= "Enviado de: BillWise\n";
    $message .= 'Data: ' . date('d/m/Y H:i:s') . "\n";
    
    $headers = "From: noreply@billwise.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Tentar enviar email (pode falhar em ambiente local sem servidor SMTP configurado)
    @mail($to, $subject, $message, $headers);
    
    echo json_encode([
        'success' => true,
        'message' => 'Feedback enviado com sucesso'
    ]);
    
} catch (PDOException $e) {
    error_log("Erro ao salvar feedback: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao salvar. Tente novamente.'
    ]);
}
