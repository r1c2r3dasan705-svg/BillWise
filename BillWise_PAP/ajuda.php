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
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .help-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 50%, #0891b2 100%);
            color: #fff;
            padding: 2rem 0;
        }

        .help-hero h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .help-hero p {
            opacity: 0.92;
            max-width: 760px;
            line-height: 1.5;
        }

        .help-wrap {
            padding: 2rem 1rem 3rem;
        }

        .help-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .help-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
            padding: 1.2rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .help-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.09);
            transition: all 0.2s ease;
        }

        .help-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #eff6ff;
            color: #1d4ed8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .help-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .help-text {
            color: #475569;
            line-height: 1.55;
            margin: 0;
        }

        .help-steps {
            margin: 0;
            padding-left: 1rem;
            color: #334155;
            line-height: 1.55;
        }

        .help-steps li {
            margin-bottom: 0.35rem;
        }

        .help-actions {
            max-width: 1200px;
            margin: 1rem auto 0;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .help-hero h1 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/sidebar.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <section class="help-hero">
                    <div class="container">
                        <h1>Centro de Ajuda</h1>
                        <p>Aqui encontras como funciona cada area do BillWise. Segue os passos rapidos de cada cartao para usares tudo sem duvidas.</p>
                    </div>
                </section>

                <section class="help-wrap">
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
                            <h2 class="help-title">Definicoes da conta</h2>
                            <p class="help-text">Atualiza dados pessoais, altera palavra-passe e gere a tua conta.</p>
                            <ol class="help-steps">
                                <li>Abre <strong>Definicoes</strong>.</li>
                                <li>Atualiza nome, email e password.</li>
                                <li>Usa eliminar conta apenas se for definitivo.</li>
                            </ol>
                        </article>
                    </div>

                    <div class="help-actions">
                        <a class="btn btn-primary" href="dashboard.php">Voltar ao Painel</a>
                        <a class="btn btn-outline" href="feedback.php">Contactar Suporte</a>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
