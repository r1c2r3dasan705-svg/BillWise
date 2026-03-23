<?php
/*
|--------------------------------------------------------------------------
| Configuração da base de dados
|--------------------------------------------------------------------------
| Define os dados de ligação e expõe uma ligação PDO reutilizável para
| todo o restante código da aplicação.
*/

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'billwise_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/*
|--------------------------------------------------------------------------
| Ligação PDO reutilizável
|--------------------------------------------------------------------------
| Tenta ligar primeiro ao host principal e, em caso de falha, tenta o
| fallback local antes de devolver uma resposta de erro ao cliente.
*/
function getPDO()
{
    static $pdo = null;

    if ($pdo) {
        return $pdo;
    }

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
        'message' => 'A ligação à base de dados falhou. Verifique o MySQL do XAMPP e a existência da base "' . DB_NAME . '". Erro: ' . ($lastError ? $lastError->getMessage() : 'desconhecido'),
    ]);
    exit;
}

$pdo = getPDO();
?>
