
<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();
// API para enviar email de suporte a partir do formulário de contacto
function smtp_read_line($socket)
{
    $data = '';
    while (($line = fgets($socket, 515)) !== false) {
        $data .= $line;
        if (strlen($line) >= 4 && $line[3] === ' ') {
            break;
        }
    }
    return $data;
}
function smtp_expect($socket, $codes)
{
    $resp = smtp_read_line($socket);
    $code = (int) substr($resp, 0, 3);
    if (!in_array($code, (array) $codes, true)) {
        throw new Exception('SMTP resposta inesperada: ' . trim($resp));
    }
    return $resp;
}
// Envia comando SMTP e espera resposta
function smtp_send_cmd($socket, $cmd, $expect)
{
    fwrite($socket, $cmd . "\r\n");
    return smtp_expect($socket, $expect);
}
// Envia email usando SMTP direto (sem bibliotecas externas)
function smtp_send_mail($to, $subject, $body, $replyTo)
{
    $host = 'smtp.gmail.com';
    $port = 587;
    $username = 'supbillwise@gmail.com';
    $password = 'tvti owub tlls ymwj';
    $from = 'supbillwise@gmail.com';
    $fromName = 'BillWise Suporte';

    $socket = stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 20);
    if (!$socket) {
        throw new Exception('Falha ao ligar SMTP: ' . $errstr);
    }
// Definir timeout para operações SMTP
    stream_set_timeout($socket, 20);

    try {
        smtp_expect($socket, 220);
        smtp_send_cmd($socket, 'EHLO localhost', 250);
        smtp_send_cmd($socket, 'STARTTLS', 220);
        $crypto = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        if ($crypto !== true) {
            throw new Exception('Falha ao iniciar TLS com SMTP.');
        }

        smtp_send_cmd($socket, 'EHLO localhost', 250);
        smtp_send_cmd($socket, 'AUTH LOGIN', 334);
        smtp_send_cmd($socket, base64_encode($username), 334);
        smtp_send_cmd($socket, base64_encode($password), 235);
        smtp_send_cmd($socket, 'MAIL FROM:<' . $from . '>', 250);
        smtp_send_cmd($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
        smtp_send_cmd($socket, 'DATA', 354);

        $headers = [];
        $headers[] = 'From: ' . $fromName . ' <' . $from . '>';
        $headers[] = 'To: <' . $to . '>';
        $headers[] = 'Reply-To: ' . $replyTo;
        $headers[] = 'Subject: =?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers[] = 'Date: ' . date('r');
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';

        $payload = implode("\r\n", $headers) . "\r\n\r\n" . $body;
        $payload = preg_replace('/(^|\r\n)\./', '$1..', $payload);

        fwrite($socket, $payload . "\r\n.\r\n");
        smtp_expect($socket, 250);
        smtp_send_cmd($socket, 'QUIT', 221);

        fclose($socket);
        return true;
    } catch (Exception $e) {
        fclose($socket);
        throw $e;
    }
}
// Receber dados JSON do formulário de contacto
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Dados invalidos']);
    exit;
}

$nome = trim($data['nome'] ?? '');
$email = trim($data['email'] ?? '');
$assunto = trim($data['assunto'] ?? '');
$mensagem = trim($data['mensagem'] ?? '');

if ($nome === '' || $email === '' || $assunto === '' || $mensagem === '') {
    echo json_encode(['success' => false, 'error' => 'Preencha todos os campos']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Email invalido']);
    exit;
}

try {
    $to = 'supbillwise@gmail.com';
    $subject = '[SUPORTE] ' . $assunto;
    $mailBody = "Nome: " . $nome . "\n";
    $mailBody .= "Email: " . $email . "\n";
    $mailBody .= "Assunto: " . $assunto . "\n\n";
    $mailBody .= "Mensagem:\n" . $mensagem . "\n\n";
    $mailBody .= "---\n";
    $mailBody .= "Enviado de: BillWise FAQ (Utilizador nao logado)\n";
    $mailBody .= "Data: " . date('d/m/Y H:i:s') . "\n";
// Enviar email usando SMTP direto
    smtp_send_mail($to, $subject, $mailBody, $email);
    
    echo json_encode([
        'success' => true,
        'message' => 'Mensagem enviada com sucesso'
    ]);
} catch (Exception $e) {
    error_log('Erro ao enviar email: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao enviar mensagem. Tente novamente mais tarde.'
    ]);
}

