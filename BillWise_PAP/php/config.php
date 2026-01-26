<?php
// Configuração da Base de Dados
// Atualize com as credenciais do seu ambiente

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'billwise_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP: password vazia por padrão

// Função para obter conexão PDO reutilizável (singleton)
function getPDO() {
    static $pdo = null;
    if ($pdo) return $pdo;

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ligação à base de dados falhou: ' . $e->getMessage()]);
        exit;
    }
}

// Criar variável global para compatibilidade com código legado
$pdo = getPDO();
?>