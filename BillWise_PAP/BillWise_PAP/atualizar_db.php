<?php
// Script para adicionar colunas de recuperação de password à base de dados

require_once 'php/config.php';

try {
    // Verificar se a coluna já existe
    $stmt = $pdo->query("SHOW COLUMNS FROM utilizadores LIKE 'codigo_recuperacao'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE utilizadores ADD COLUMN codigo_recuperacao VARCHAR(6) DEFAULT NULL");
        echo "Coluna 'codigo_recuperacao' adicionada com sucesso!<br>";
    } else {
        echo "Coluna 'codigo_recuperacao' já existe.<br>";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM utilizadores LIKE 'codigo_expiracao'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE utilizadores ADD COLUMN codigo_expiracao DATETIME DEFAULT NULL");
        echo "Coluna 'codigo_expiracao' adicionada com sucesso!<br>";
    } else {
        echo "Coluna 'codigo_expiracao' já existe.<br>";
    }
    
    echo "<br>Base de dados atualizada com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

