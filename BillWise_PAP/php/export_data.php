<?php
// Exportação de dados para ficheiros CSV
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'config.php';

// Obter ID do utilizador e tipo de exportação
$user_id = $_SESSION['user_id'];
$tipo = $_GET['tipo'] ?? 'despesas';

try {
    if ($tipo === 'despesas') {
        // Exportar despesas
        $stmt = $pdo->prepare("
            SELECT categoria, descricao, valor, data, criado_em 
            FROM despesas 
            WHERE utilizador_id = ? 
            ORDER BY data DESC
        ");
        $stmt->execute([$user_id]);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $filename = "despesas_" . date('Y-m-d') . ".csv";
        $headers = ['Categoria', 'Descrição', 'Valor (€)', 'Data', 'Registado Em'];
        
    } elseif ($tipo === 'orcamentos') {
        // Exportar orçamentos
        $stmt = $pdo->prepare("
            SELECT nome, limite, gasto, criado_em 
            FROM orcamentos 
            WHERE utilizador_id = ? 
            ORDER BY criado_em DESC
        ");
        $stmt->execute([$user_id]);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $filename = "orcamentos_" . date('Y-m-d') . ".csv";
        $headers = ['Nome', 'Limite (€)', 'Gasto (€)', 'Criado Em'];
        
    } elseif ($tipo === 'relatorio') {
        // Relatório completo
        $stmt = $pdo->prepare("
            SELECT 
                'Despesa' as tipo,
                categoria as nome,
                descricao,
                valor,
                data as data_registro
            FROM despesas 
            WHERE utilizador_id = ?
            UNION ALL
            SELECT 
                'Orçamento' as tipo,
                nome,
                '-' as descricao,
                limite as valor,
                DATE(criado_em) as data_registro
            FROM orcamentos 
            WHERE utilizador_id = ?
            ORDER BY data_registro DESC
        ");
        $stmt->execute([$user_id, $user_id]);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $filename = "relatorio_completo_" . date('Y-m-d') . ".csv";
        $headers = ['Tipo', 'Nome/Categoria', 'Descrição', 'Valor (€)', 'Data'];
    }
    
    // Criar CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // BOM para UTF-8 no Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Cabeçalhos
    fputcsv($output, $headers, ';');
    
    // Dados
    foreach ($dados as $row) {
        fputcsv($output, $row, ';');
    }
    
    fclose($output);
    exit;
    
} catch (Exception $e) {
    die("Erro ao exportar dados: " . $e->getMessage());
}
?>


