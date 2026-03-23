<?php
require_once 'php/base.php';

billwise_require_auth();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda - BillWise</title>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <link rel="stylesheet" href="assets/css/botoes.css?v=3">
    <link rel="stylesheet" href="assets/css/formularios.css?v=3">
    <link rel="stylesheet" href="assets/css/modais.css?v=4">
    <link rel="stylesheet" href="assets/css/painel.css?v=2">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/barra_lateral.php'; ?>
        <div class="main-content-wrapper">
            <main id="main-content" class="main">
                <div class="container">
                    <section class="dashboard-hero">
                        <div class="hero-greeting">
                            <h1>Ajuda Rápida</h1>
                            <p>O essencial para usar o BillWise sem teres de ler tudo.</p>
                        </div>
                    </section>

                    <section class="page-section active">
                        <div class="help-grid">
                            <article class="help-card">
                                <h2 class="help-title">Despesas</h2>
                                <p class="help-text">Usa esta área para registar tudo o que gastas. Quanto mais certo estiver aqui, mais útil fica o painel.</p>
                                <ol class="help-steps">
                                    <li>Adiciona valor, categoria, data e descrição.</li>
                                    <li>Filtra por categoria para encontrar gastos mais depressa.</li>
                                    <li>Edita ou apaga quando precisares de corrigir um registo.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <h2 class="help-title">Orçamentos</h2>
                                <p class="help-text">Define um limite por categoria para perceber se estás a gastar dentro do planeado.</p>
                                <ol class="help-steps">
                                    <li>Cria um orçamento para cada categoria importante.</li>
                                    <li>Escolhe um limite mensal realista.</li>
                                    <li>Vê a barra de progresso para perceber quanto já foi usado.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <h2 class="help-title">Simulador de Investimentos</h2>
                                <p class="help-text">Serve para testar cenários e perceber como o dinheiro pode crescer ao longo do tempo.</p>
                                <ol class="help-steps">
                                    <li><strong>ETF</strong>: fundo que junta vários ativos num só produto, para investir de forma diversificada.</li>
                                    <li><strong>Risco</strong>: quanto maior o potencial de ganho, maior pode ser a oscilação.</li>
                                    <li><strong>Horizonte temporal</strong>: quanto mais tempo deixares investido, maior o efeito dos juros compostos.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <h2 class="help-title">Painel</h2>
                                <p class="help-text">É o teu resumo geral. Mostra rapidamente o que gastaste, o que ainda tens disponível e onde estás a gastar mais.</p>
                                <ol class="help-steps">
                                    <li>Consulta os indicadores principais no topo.</li>
                                    <li>Usa as ações rápidas para abrir despesas, orçamentos e simuladores.</li>
                                    <li>Vê as categorias com mais peso no mês.</li>
                                </ol>
                            </article>
                        </div>
                    </section>

                    <div class="help-actions">
                        <a class="btn btn-primary" href="painel.php">Voltar ao Painel</a>
                        <a class="btn btn-outline" href="feedback.php">Pedir Ajuda</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>


