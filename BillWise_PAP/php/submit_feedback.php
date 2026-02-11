<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();

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

function smtp_send_cmd($socket, $cmd, $expect)
{
    fwrite($socket, $cmd . "\r\n");
    return smtp_expect($socket, $expect);
}

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

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Dados invalidos']);
    exit;
}

$nome = trim($data['nome'] ?? '');
$email = trim($data['email'] ?? '');
$tipo = trim($data['tipo'] ?? 'sugestao');
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
    require_once 'config.php';

    $utilizador_id = $_SESSION['user_id'] ?? null;

    $stmt = $pdo->prepare(
        "INSERT INTO feedback (utilizador_id, nome, email, tipo, assunto, mensagem, status) VALUES (?, ?, ?, ?, ?, ?, 'pendente')"
    );
    $stmt->execute([$utilizador_id, $nome, $email, $tipo, $assunto, $mensagem]);

    $to = 'supbillwise@gmail.com';
    $subject = '[' . strtoupper($tipo) . '] ' . $assunto;
    $mailBody = "Tipo: " . ucfirst($tipo) . "\n";
    $mailBody .= "Nome: " . $nome . "\n";
    $mailBody .= "Email: " . $email . "\n";
    $mailBody .= "Assunto: " . $assunto . "\n\n";
    $mailBody .= "Mensagem:\n" . $mensagem . "\n\n";
    $mailBody .= "---\n";
    $mailBody .= "Enviado de: BillWise\n";
    $mailBody .= "Data: " . date('d/m/Y H:i:s') . "\n";

    try {
        smtp_send_mail($to, $subject, $mailBody, $email);
        echo json_encode([
            'success' => true,
            'message' => 'Feedback enviado com sucesso',
            'email_sent' => true,
        ]);
    } catch (Exception $mailError) {
        $logDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }

        $logFile = $logDir . DIRECTORY_SEPARATOR . 'feedback_email_fallback.log';
        $entry = '[' . date('Y-m-d H:i:s') . "]\n" . $subject . "\n" . $mailBody . "\n";
        @file_put_contents($logFile, $entry, FILE_APPEND);

        error_log('Falha no SMTP do feedback: ' . $mailError->getMessage());

        echo json_encode([
            'success' => true,
            'message' => 'Feedback guardado com sucesso, mas o envio de email falhou.',
            'email_sent' => false,
        ]);
    }
} catch (PDOException $e) {
    error_log('Erro ao salvar feedback: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao salvar. Tente novamente.',
    ]);
}

