<?php
// API para solicitar código de recuperação de password
// O código · gerado aleatoriamente e enviado por email (não guardado na BD)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Funções SMTP 
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
    $fromName = 'BillWise';

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
// Continuar autenticação e envio de email
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
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
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

session_start();
require_once 'configuracao.php';
$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit;
}

// Receber dados JSON do formulário de recuperação
$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'error' => 'Email inválido']);
    exit;
}

try {
    // Verificar se o email existe
    $stmt = $pdo->prepare("SELECT id, nome FROM utilizadores WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("User found for email $email: " . ($user ? 'yes id=' . $user['id'] : 'no'));
    
    if (!$user) {
        // Não revelar se o email existe ou não
        echo json_encode(['success' => true, 'message' => 'Se o email existir, receberá um código de recuperação']);
        exit;
    }
    
    // Gerar código de 6 dígitos ALEATÓRIO (novo código a cada pedido)
    $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    
    // Guardar o código na SESSÃO (não na BD) - expira em 15 minutos
    $_SESSION['codigo_recuperacao'] = [
        'codigo' => $codigo,
        'email' => $email,
        'expiracao' => time() + (15 * 60) // 15 minutos
    ];
    error_log("Code generated and stored in session: $codigo for $email, expiry=" . $_SESSION['codigo_recuperacao']['expiracao']);
    

    $assunto = "Código de Recuperação de Password - BillWise";
    $mensagem = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .code { font-size: 32px; font-weight: bold; color: #0b2b47; letter-spacing: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Recuperação de Password - BillWise</h2>
            <p>Olá {$user['nome']},</p>
            <p>O seu código de recuperação de password é:</p>
            <p class='code'>{$codigo}</p>
            <p>Este código · válido por 15 minutos.</p>
            <p>Se não solicitou esta recuperação, ignore este email.</p>
        </div>
    </body>
    </html>
    ";
    
    smtp_send_mail($email, $assunto, $mensagem, 'supbillwise@gmail.com');
    error_log("Email sent to $email with code $codigo");
    
    echo json_encode(['success' => true, 'message' => 'Se o email existir, receberá um código de recuperação']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erro ao processar pedido: ' . $e->getMessage()]);
}


