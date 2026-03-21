<?php
// Pagina de ajuda para utilizadores autenticados
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajuda - BillWise</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/sidebar.css?v=1">
    <link rel="stylesheet" href="assets/css/buttons.css?v=3">
    <link rel="stylesheet" href="assets/css/forms.css?v=3">
    <link rel="stylesheet" href="assets/css/modals.css?v=4">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/sidebar.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container">
                    <!-- Hero Section -->
                    <section class="dashboard-hero">
                        <div class="hero-greeting">
                            <h1>Centro de Ajuda</h1>
                            <p>Encontre respostas para as suas dúvidas</p>
                        </div>
                    </section>
                    
                    <section class="page-section active">
                        <div class="help-grid">
                            <article class="help-card">
                                <span class="help-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg>
                                </span>
                                <h2 class="help-title">Painel</h2>
                                <p class="help-text">Mostra o resumo financeiro do mes: despesas, orcamento restante, progresso e ultimos registos.</p>
                                <ol class="help-steps">
                                    <li>Abre o menu <strong>Painel</strong>.</li>
                                    <li>Consulta os indicadores no topo.</li>
                                    <li>Usa os atalhos para abrir despesas e orcamentos.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <span class="help-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="1" y="4" width="22" height="16" rx="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </span>
                                <h2 class="help-title">Despesas</h2>
                                <p class="help-text">Permite criar, editar e apagar despesas. Cada despesa pode ter categoria, data e descricao.</p>
                                <ol class="help-steps">
                                    <li>Clica em <strong>Adicionar Despesa</strong>.</li>
                                    <li>Preenche valor, categoria e data.</li>
                                    <li>Guarda para atualizar o total e os graficos.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <span class="help-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <circle cx="12" cy="12" r="6"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                    </svg>
                                </span>
                                <h2 class="help-title">Orcamentos</h2>
                                <p class="help-text">Define limites por categoria e acompanha o gasto em tempo real com barra de progresso.</p>
                                <ol class="help-steps">
                                    <li>Cria um orcamento por categoria.</li>
                                    <li>Define limite mensal.</li>
                                    <li>Revê a percentagem usada e ajusta quando preciso.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <span class="help-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                                        <line x1="8" y1="6" x2="16" y2="6"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                        <line x1="8" y1="18" x2="16" y2="18"></line>
                                    </svg>
                                </span>
                                <h2 class="help-title">Calculadora PPR</h2>
                                <p class="help-text">Simula poupanca para reforma com capital inicial, reforco mensal, taxa anual e anos.</p>
                                <ol class="help-steps">
                                    <li>Introduz os dados de simulacao.</li>
                                    <li>Clica em <strong>Calcular</strong>.</li>
                                    <li>Compara total investido e retorno estimado.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <span class="help-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </span>
                                <h2 class="help-title">Feedback</h2>
                                <p class="help-text">Serve para enviar sugestoes, reportar bugs e pedir apoio. O registo fica guardado no sistema.</p>
                                <ol class="help-steps">
                                    <li>Seleciona o tipo de mensagem.</li>
                                    <li>Preenche assunto e detalhes.</li>
                                    <li>Envia para a equipa de suporte.</li>
                                </ol>
                            </article>

                            <article class="help-card">
                                <span class="help-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M12 1v6m0 10v6m11-11h-6M7 12H1"></path>
                                    </svg>
                                </span>
                                <h2 class="help-title">Definições da conta</h2>
                                <p class="help-text">Atualiza dados pessoais, altera palavra-passe e gere a tua conta.</p>
                                <ol class="help-steps">
                                    <li>Abre <strong>Definições</strong>.</li>
                                    <li>Atualiza nome, email e password.</li>
                                    <li>Usa eliminar conta apenas se for definitivo.</li>
                                </ol>
                            </article>
                        </div>
                    </section>

                    <div class="help-actions">
href="painel.php">Voltar ao Painel
                        <a class="btn btn-outline" href="feedback.php">Contactar Suporte</a>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>

