<?php
// Restringe acesso a utilizadores autenticados
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Obter dados do utilizador da sessão
$user_id = $_SESSION['user_id'];
$user_name = htmlentities($_SESSION['name']);

// Conectar à base de dados
try {
    require_once 'php/config.php';
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Inicializar variáveis com valores padrão
$despesas_mes = 0;
$orcamento_total = 0;
$orcamento_restante = 0;
$percentual_usado = 0;
$ultimas_despesas = [];
$despesas_por_categoria = [];

try {
    // Total de despesas do mês atual
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(valor), 0) as total
        FROM despesas
        WHERE utilizador_id = ? AND MONTH(data) = MONTH(CURRENT_DATE()) AND YEAR(data) = YEAR(CURRENT_DATE())
    ");
    $stmt->execute([$user_id]);
    $despesas_mes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Total de orçamentos
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(limite), 0) as total_limite, COALESCE(SUM(gasto), 0) as total_gasto
        FROM orcamentos
        WHERE utilizador_id = ?
    ");
    $stmt->execute([$user_id]);
    $orcamentos = $stmt->fetch(PDO::FETCH_ASSOC);
    $orcamento_total = $orcamentos['total_limite'];
    $orcamento_restante = $orcamento_total - $despesas_mes;
    $percentual_usado = $orcamento_total > 0 ? round(($despesas_mes / $orcamento_total) * 100) : 0;

    // Despesas por categoria
    $stmt = $pdo->prepare("
        SELECT categoria, SUM(valor) as total
        FROM despesas
        WHERE utilizador_id = ? AND MONTH(data) = MONTH(CURRENT_DATE()) AND YEAR(data) = YEAR(CURRENT_DATE())
        GROUP BY categoria
        ORDER BY total DESC
        LIMIT 5
    ");
    $stmt->execute([$user_id]);
    $despesas_por_categoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
    
    // Últimas despesas
    $stmt = $pdo->prepare("
        SELECT * FROM despesas
        WHERE utilizador_id = ?
        ORDER BY data DESC, id DESC
        LIMIT 5
    ");
    $stmt->execute([$user_id]);
    $ultimas_despesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erro ao buscar dados do dashboard: " . $e->getMessage());
    // Continua com valores padrão
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - BillWise</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/sidebar.css?v=1">
    <link rel="stylesheet" href="assets/css/buttons.css?v=3">
    <link rel="stylesheet" href="assets/css/forms.css?v=3">
    <link rel="stylesheet" href="assets/css/modals.css?v=3">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/sidebar.php'; ?>
        <div class="main-content-wrapper">
            <main id="main-content" class="main">
                <div class="container">
                    <!-- Welcome Section -->
                    <section class="dashboard-hero">
                        <div class="dashboard-hero-inner">
                            <div class="hero-greeting">
                                <h1>Bem-vindo de volta, <span class="user-name"><?php echo $user_name; ?></span>!</h1>
                                <p>Aqui está um resumo da sua jornada financeira</p>
                            </div>
                            <div class="hero-actions">
                                <a class="btn btn-primary" href="despesas.php">Nova Despesa</a>
                                <a class="btn btn-outline" href="orcamento.php">Definir Orçamento</a>
                            </div>
                        </div>
                    </section>

                    <!-- KPI + Progress Overview -->
                    <section class="dashboard-overview">
                        <div class="overview-grid">
                            <div class="overview-main">
                                <div class="kpi-grid">
                    <div class="kpi-card animate-on-scroll">
                        <div class="kpi-icon danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                        <div class="kpi-content">
                            <h3>Despesas do Mês</h3>
                            <p class="kpi-value">€<?php echo number_format($despesas_mes, 2, ',', '.'); ?></p>
                            <span class="kpi-label">Mês atual</span>
                        </div>
                    </div>

                    <div class="kpi-card animate-on-scroll">
                        <div class="kpi-icon success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <div class="kpi-content">
                            <h3>Orçamento Restante</h3>
                            <p class="kpi-value">€<?php echo number_format($orcamento_restante, 2, ',', '.'); ?></p>
                            <span class="kpi-label"><?php echo $percentual_usado; ?>% do orçamento usado</span>
                        </div>
                    </div>

                    <div class="kpi-card animate-on-scroll">
                        <div class="kpi-icon primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                        <div class="kpi-content">
                            <h3>Economia Mensal</h3>
                            <p class="kpi-value">€<?php echo number_format(max(0, $orcamento_total - $despesas_mes), 2, ',', '.'); ?></p>
                            <span class="kpi-label">Poupado este mês</span>
                        </div>
                    </div>

                                </div>
                            </div>
                            <div class="overview-side">
                                <div class="progress-card">
                                    <h2><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>Progresso Financeiro</h2>
                                    <div class="progress-stats">
                                        <div class="progress-item">
                                            <div class="progress-header">
                                                <span>Controle de Orçamento</span>
                                                <span class="progress-percentage"><?php echo min(100, $percentual_usado); ?>%</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill <?php echo $percentual_usado > 100 ? 'danger' : ($percentual_usado > 80 ? 'warning' : 'success'); ?>" 
                                                     style="width: <?php echo min(100, $percentual_usado); ?>%"></div>
                                            </div>
                                            <small class="progress-label">
                                                <?php 
                                                if ($percentual_usado > 100) echo 'Orçamento excedido!';
                                                elseif ($percentual_usado > 80) echo 'Próximo do limite';
                                                else echo 'Dentro do orçamento';
                                                ?>
                                            </small>
                                        </div>

                                        <div class="progress-item" style="margin-top: 1.5rem;">
                                            <div class="progress-header">
                                                <span>Meta de Poupança</span>
                                                <span class="progress-percentage">
                                                    <?php 
                                                    $meta_poupanca = $orcamento_total * 0.2; // 20% do orçamento
                                                    $poupanca_atual = max(0, $orcamento_total - $despesas_mes);
                                                    $percentual_poupanca = $meta_poupanca > 0 ? min(100, round(($poupanca_atual / $meta_poupanca) * 100)) : 0;
                                                    echo $percentual_poupanca;
                                                    ?>%
                                                </span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill success" style="width: <?php echo $percentual_poupanca; ?>%"></div>
                                            </div>
                                            <small class="progress-label">
                                                Meta: €<?php echo number_format($meta_poupanca, 2, ',', '.'); ?> | 
                                                Atual: €<?php echo number_format($poupanca_atual, 2, ',', '.'); ?>
                                            </small>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

            <!-- Charts & Analytics Section -->
            <section class="dashboard-content">
                <div class="content-row">
                    <!-- Expenses Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h2>Despesas por Categoria</h2>
                            <div class="chart-controls">
                                <select class="chart-period">
                                    <option>Este Mês</option>
                                    <option>Últimos 3 Meses</option>
                                    <option>Este Ano</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-container">
                            <div class="expense-categories">
                                <?php
                                if (count($despesas_por_categoria) > 0):
                                    $total_despesas = array_sum(array_column($despesas_por_categoria, 'total'));
                                    $cores = ['#0b2b47', '#1b6b63', '#b08952', '#7a1f1f', '#123a58'];
                                    foreach ($despesas_por_categoria as $index => $cat):
                                        $percentual = $total_despesas > 0 ? round(($cat['total'] / $total_despesas) * 100) : 0;
                                        $cor = $cores[$index % count($cores)];
                                ?>
                                <div class="category-bar">
                                    <div class="category-label">
                                        <span class="category-name"><?php echo htmlentities($cat['categoria']); ?></span>
                                        <span class="category-amount">€<?php echo number_format($cat['total'], 2, ',', '.'); ?> (<?php echo $percentual; ?>%)</span>
                                    </div>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: <?php echo $percentual; ?>%; background: <?php echo $cor; ?>;"></div>
                                    </div>
                                </div>
                                <?php
                                    endforeach;
                                else:
                                ?>
                                <div style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                    <p>Nenhuma despesa registada este mês.</p>
                                    <a href="despesas.php" class="btn btn-primary" style="margin-top: 1rem;">Adicionar Despesa</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="actions-stack">
                    <div class="quick-actions-card">
                        <h2>Ações Rápidas</h2>
                        <div class="actions-list">
                            <a href="despesas.php" class="action-button">
                                <span class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></span>
                                <span class="action-text">
                                    <strong>Adicionar Despesa</strong>
                                    <small>Registar nova despesa</small>
                                </span>
                            </a>
                            <a href="orcamento.php" class="action-button">
                                <span class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg></span>
                                <span class="action-text">
                                    <strong>Definir Orçamento</strong>
                                    <small>Criar limite de gastos</small>
                                </span>
                            </a>
                            <a href="calculadora_ppr.php" class="action-button">
                                <span class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"></rect><line x1="8" y1="6" x2="16" y2="6"></line><line x1="16" y1="14" x2="16" y2="14"></line><line x1="8" y1="14" x2="8" y2="14"></line><line x1="12" y1="14" x2="12" y2="14"></line><line x1="16" y1="18" x2="16" y2="18"></line><line x1="8" y1="18" x2="8" y2="18"></line><line x1="12" y1="18" x2="12" y2="18"></line></svg></span>
                                <span class="action-text">
                                    <strong>Calcular PPR</strong>
                                    <small>Simular reforma</small>
                                </span>
                            </a>
                            <a href="feedback.php" class="action-button">
                                <span class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span>
                                <span class="action-text">
                                    <strong>Feedback</strong>
                                    <small>Dê a sua opinião</small>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Export Card -->
                    <div class="quick-actions-card">
                        <h2><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 0.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>Exportar Dados</h2>
                        <div class="actions-list">
                            <a href="php/export_data.php?tipo=despesas" class="action-button" download>
                                <span class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                                <span class="action-text">
                                    <strong>Exportar Despesas</strong>
                                    <small>Ficheiro CSV</small>
                                </span>
                            </a>
                            <a href="php/export_data.php?tipo=orcamentos" class="action-button" download>
                                <span class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg></span>
                                <span class="action-text">
                                    <strong>Exportar Orçamentos</strong>
                                    <small>Ficheiro CSV</small>
                                </span>
                            </a>
                            <a href="php/export_data.php?tipo=relatorio" class="action-button" download>
                                <span class="action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></span>
                                <span class="action-text">
                                    <strong>Relatório Completo</strong>
                                    <small>Todos os dados</small>
                                </span>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="dashboard-split">
                    <div class="split-grid split-grid--single">
                <!-- Recent Expenses -->
                <div class="recent-section data-card">
                    <div class="section-title">
                        <h2>Últimas Despesas</h2>
                        <a href="despesas.php" class="link-more">Ver Todas →</a>
                    </div>
                    <div class="expenses-table">
                        <div class="table-header">
                            <div class="col-category">Categoria</div>
                            <div class="col-amount">Valor</div>
                            <div class="col-date">Data</div>
                            <div class="col-description">Descrição</div>
                        </div>
                        <div class="table-body">
                            <?php if (count($ultimas_despesas) > 0): ?>
                                <?php foreach ($ultimas_despesas as $despesa): 
                                    $data_formatada = date('d/m/Y', strtotime($despesa['data']));
                                    $hoje = date('Y-m-d');
                                    $ontem = date('Y-m-d', strtotime('-1 day'));
                                    
                                    if ($despesa['data'] == $hoje) {
                                        $data_display = 'Hoje';
                                    } elseif ($despesa['data'] == $ontem) {
                                        $data_display = 'Ontem';
                                    } else {
                                        $data_display = $data_formatada;
                                    }
                                ?>
                            <div class="table-row">
                                <div class="col-category">
                                    <span class="badge badge-blue"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span>
                                    <?php echo htmlentities($despesa['categoria']); ?>
                                </div>
                                <div class="col-amount">€<?php echo number_format($despesa['valor'], 2, ',', '.'); ?></div>
                                <div class="col-date"><?php echo $data_display; ?></div>
                                <div class="col-description"><?php echo htmlentities($despesa['descricao'] ?? '-'); ?></div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <div style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                <p>Nenhuma despesa registada ainda.</p>
                                <a href="despesas.php" class="btn btn-primary" style="margin-top: 1rem;">Adicionar Primeira Despesa</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                </div>
                </div>
                </section>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/notifications.js?v=1"></script>
    <script src="assets/js/main.js?v=3"></script>
</body>
</html>


