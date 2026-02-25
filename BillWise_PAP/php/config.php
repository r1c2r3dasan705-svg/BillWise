<?php
// Configuracao da Base de Dados

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'billwise_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Funcao para obter conexao PDO reutilizavel (singleton)
function getPDO() {
    static $pdo = null;
    if ($pdo) return $pdo;

    $hosts = [DB_HOST, 'localhost'];
    $lastError = null;

    foreach ($hosts as $host) {
        $dsn = 'mysql:host=' . $host . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 5,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            $lastError = $e;
        }
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Ligacao a base de dados falhou. Verifique se o MySQL do XAMPP esta ativo e se a BD "' . DB_NAME . '" existe. Erro: ' . ($lastError ? $lastError->getMessage() : 'desconhecido')
    ]);
    exit;
}

// Criar variavel global para compatibilidade com codigo legado
$pdo = getPDO();
?>
