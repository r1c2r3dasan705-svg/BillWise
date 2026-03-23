<?php
require_once 'php/base.php';

billwise_require_auth();
require_once 'php/configuracao.php';

$user_id = $_SESSION['user_id'];
$user_name = billwise_user_name();

$despesas_mes = 0.0;
$orcamento_total = 0.0;
$orcamento_restante = 0.0;
$percentual_usado = 0;
$ultimas_despesas = [];
$despesas_por_categoria = [];

try {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(valor), 0) AS total
        FROM despesas
        WHERE utilizador_id = ? AND MONTH(data) = MONTH(CURRENT_DATE()) AND YEAR(data) = YEAR(CURRENT_DATE())
    ");
    $stmt->execute([$user_id]);
    $despesas_mes = (float) $stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(limite), 0) AS total_limite
        FROM orcamentos
        WHERE utilizador_id = ?
    ");
    $stmt->execute([$user_id]);
    $orcamento_total = (float) $stmt->fetchColumn();

    $orcamento_restante = $orcamento_total - $despesas_mes;
    $percentual_usado = $orcamento_total > 0 ? (int) round(($despesas_mes / $orcamento_total) * 100) : 0;

    $stmt = $pdo->prepare("
        SELECT categoria, SUM(valor) AS total
        FROM despesas
        WHERE utilizador_id = ? AND MONTH(data) = MONTH(CURRENT_DATE()) AND YEAR(data) = YEAR(CURRENT_DATE())
        GROUP BY categoria
        ORDER BY total DESC
        LIMIT 5
    ");
    $stmt->execute([$user_id]);
    $despesas_por_categoria = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT categoria, descricao, valor, data
        FROM despesas
        WHERE utilizador_id = ?
        ORDER BY data DESC, id DESC
        LIMIT 5
    ");
    $stmt->execute([$user_id]);
    $ultimas_despesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Erro ao carregar painel: ' . $e->getMessage());
}

$meta_poupanca = $orcamento_total * 0.2;
$poupanca_atual = max(0, $orcamento_total - $despesas_mes);
$percentual_poupanca = $meta_poupanca > 0 ? min(100, (int) round(($poupanca_atual / $meta_poupanca) * 100)) : 0;
$total_despesas_categorias = array_sum(array_map(static fn($item) => (float) $item['total'], $despesas_por_categoria));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - BillWise</title>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <link rel="stylesheet" href="assets/css/botoes.css?v=3">
    <link rel="stylesheet" href="assets/css/formularios.css?v=3">
    <link rel="stylesheet" href="assets/css/modais.css?v=3">
    <link rel="stylesheet" href="assets/css/painel.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/barra_lateral.php'; ?>
        <div class="main-content-wrapper">
            <main id="main-content" class="main">
                <div class="container">
                    <section class="dashboard-hero">
                        <div class="dashboard-hero-inner">
                            <div class="hero-greeting">
                                <h1>Bem-vindo, <span class="user-name"><?php echo $user_name; ?></span></h1>
                                <p>Resumo financeiro do mês atual com acessos rápidos às áreas principais.</p>
                            </div>
                            <div class="hero-actions">
                                <a class="btn btn-primary" href="despesas.php">Nova Despesa</a>
                                <a class="btn btn-outline" href="orcamento.php">Gerir Orçamento</a>
                            </div>
                        </div>
                    </section>

                    <section class="dashboard-overview">
                        <div class="overview-grid">
                            <div class="overview-main">
                                <div class="kpi-grid">
                                    <div class="kpi-card animate-on-scroll">
                                        <div class="kpi-content">
                                            <h3>Despesas do Mês</h3>
                                            <p class="kpi-value"><?php echo billwise_currency($despesas_mes); ?></p>
                                            <span class="kpi-label">Total registado este mês</span>
                                        </div>
                                    </div>
                                    <div class="kpi-card animate-on-scroll">
                                        <div class="kpi-content">
                                            <h3>Orçamento Total</h3>
                                            <p class="kpi-value"><?php echo billwise_currency($orcamento_total); ?></p>
                                            <span class="kpi-label"><?php echo $percentual_usado; ?>% utilizado</span>
                                        </div>
                                    </div>
                                    <div class="kpi-card animate-on-scroll">
                                        <div class="kpi-content">
                                            <h3>Saldo Disponível</h3>
                                            <p class="kpi-value"><?php echo billwise_currency($orcamento_restante); ?></p>
                                            <span class="kpi-label">Diferença entre limite e gastos</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="overview-side">
                                <div class="progress-card">
                                    <h2>Progresso Financeiro</h2>
                                    <div class="progress-stats">
                                        <div class="progress-item">
                                            <div class="progress-header">
                                                <span>Execução do orçamento</span>
                                                <span class="progress-percentage"><?php echo min(100, $percentual_usado); ?>%</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill <?php echo $percentual_usado > 100 ? 'danger' : ($percentual_usado > 80 ? 'warning' : 'success'); ?>" style="width: <?php echo min(100, $percentual_usado); ?>%"></div>
                                            </div>
                                            <small class="progress-label">
                                                <?php echo $percentual_usado > 100 ? 'Orçamento ultrapassado' : ($percentual_usado > 80 ? 'Próximo do limite' : 'Dentro do planeado'); ?>
                                            </small>
                                        </div>

                                        <div class="progress-item" style="margin-top: 1.5rem;">
                                            <div class="progress-header">
                                                <span>Meta de poupança</span>
                                                <span class="progress-percentage"><?php echo $percentual_poupanca; ?>%</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill success" style="width: <?php echo $percentual_poupanca; ?>%"></div>
                                            </div>
                                            <small class="progress-label">
                                                Meta: <?php echo billwise_currency($meta_poupanca); ?> | Atual: <?php echo billwise_currency($poupanca_atual); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="dashboard-content">
                        <div class="content-row">
                            <div class="chart-card">
                                <div class="chart-header">
                                    <h2>Despesas por Categoria</h2>
                                </div>
                                <div class="chart-container">
                                    <div class="expense-categories">
                                        <?php if ($despesas_por_categoria): ?>
                                            <?php $cores = ['#0b2b47', '#1b6b63', '#b08952', '#7a1f1f', '#123a58']; ?>
                                            <?php foreach ($despesas_por_categoria as $index => $categoria): ?>
                                                <?php
                                                $total = (float) $categoria['total'];
                                                $percentual = $total_despesas_categorias > 0 ? (int) round(($total / $total_despesas_categorias) * 100) : 0;
                                                ?>
                                                <div class="category-bar">
                                                    <div class="category-label">
                                                        <span class="category-name"><?php echo billwise_escape($categoria['categoria']); ?></span>
                                                        <span class="category-amount"><?php echo billwise_currency($total); ?> (<?php echo $percentual; ?>%)</span>
                                                    </div>
                                                    <div class="bar-container">
                                                        <div class="bar-fill" style="width: <?php echo $percentual; ?>%; background: <?php echo $cores[$index % count($cores)]; ?>;"></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                                <p>Nenhuma despesa registada este mês.</p>
                                                <a href="despesas.php" class="btn btn-primary" style="margin-top: 1rem;">Adicionar Despesa</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="actions-stack">
                                <div class="quick-actions-card">
                                    <h2>Ações Rápidas</h2>
                                    <div class="actions-list">
                                        <a href="despesas.php" class="action-button"><span class="action-text"><strong>Adicionar Despesa</strong><small>Registar um novo movimento</small></span></a>
                                        <a href="orcamento.php" class="action-button"><span class="action-text"><strong>Definir Orçamento</strong><small>Criar ou editar limites</small></span></a>
                                        <a href="calculadora_ppr.php" class="action-button"><span class="action-text"><strong>Calcular PPR</strong><small>Simular evolução da poupança</small></span></a>
                                        <a href="feedback.php" class="action-button"><span class="action-text"><strong>Enviar Feedback</strong><small>Reportar problemas ou sugestões</small></span></a>
                                        <a href="forum.php" class="action-button"><span class="action-text"><strong>Fórum</strong><small>Participar na comunidade</small></span></a>
                                    </div>
                                </div>

                                <div class="quick-actions-card" style="margin-top: 1.5rem;">
                                    <h2>Últimas Despesas</h2>
                                    <?php if ($ultimas_despesas): ?>
                                        <div class="actions-list">
                                            <?php foreach ($ultimas_despesas as $despesa): ?>
                                                <div class="action-button" style="cursor: default;">
                                                    <span class="action-text">
                                                        <strong><?php echo billwise_escape($despesa['categoria']); ?> · <?php echo billwise_currency((float) $despesa['valor']); ?></strong>
                                                        <small><?php echo billwise_escape($despesa['descricao'] ?: 'Sem descrição'); ?> · <?php echo date('d/m/Y', strtotime($despesa['data'])); ?></small>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>Ainda não existem despesas registadas.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>


