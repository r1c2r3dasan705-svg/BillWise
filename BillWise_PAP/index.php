<?php
// Página Inicial (Landing Page) do BillWise
// Apresentação da plataforma para visitantes não autenticados
// Utilizadores autenticados são redirecionados para o dashboard

// Iniciar sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirecionar utilizadores autenticados para o painel principal
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Configurar variáveis para controlar links e modais
$panel_link = '#';
$panel_open_login = true;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BillWise - Literacia Financeira Simplificada</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <link rel="stylesheet" href="assets/css/header.css?v=2">
    <link rel="stylesheet" href="assets/css/buttons.css?v=2">
    <link rel="stylesheet" href="assets/css/forms.css?v=2">
    <link rel="stylesheet" href="assets/css/modals.css?v=2">
    <link rel="stylesheet" href="assets/css/footer.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <main id="main" class="main">
        <div class="container">
            <!-- Hero Intro Section -->
            <section id="home" class="page-section active">
                <div class="hero hero-premium">
                    <div class="container">
                        <div class="hero-content">
                            <div class="hero-text">
                                <h1>Domine as suas finanças com o <span class="highlight">BillWise</span></h1>
                                <p class="hero-subtitle">A solução completa para gestão financeira pessoal. Controle despesas, defina orçamentos e simule investimentos.</p>
                                <div class="hero-actions">
                                    <?php if ($panel_open_login): ?>
                                        <a href="#" class="btn btn-primary open-login">Começar Agora</a>
                                    <?php else: ?>
                                        <a href="<?php echo $panel_link; ?>" class="btn btn-primary">Ir para o Painel</a>
                                    <?php endif; ?>
                                    <?php if ($panel_open_login): ?>
                                        <a href="#" class="btn btn-outline open-restricted">Aprender Mais</a>
                                    <?php else: ?>
                                        <a href="orcamento.php" class="btn btn-outline">Aprender Mais</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="hero-visual">
                                <div class="hero-card-container">
                                    <div class="hero-card hero-card-1">
                                        <div class="card-header">
                                            <svg class="icon card-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M4 3h2v18H4zM9 11h2v10H9zM14 7h2v14h-2zM19 15h2v6h-2z"/></svg>
                                            <span class="card-title">Dashboard Inteligente</span>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-stat">
                                                <span class="stat-label">Total Despesas</span>
                                                <span class="stat-value">€1,234</span>
                                            </div>
                                            <div class="progress-container">
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 65%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hero-card hero-card-2">
                                        <div class="card-header">
                                            <svg class="icon card-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 11h3v2h-3v3h-2v-3H8v-2h3V8h2v5z"/></svg>
                                            <span class="card-title">Orçamentos</span>
                                        </div>
                                        <div class="card-content">
                                            <div class="category-item-hero">
                                                <span>Alimentação</span>
                                                <span class="category-amount">€250</span>
                                            </div>
                                            <div class="category-item-hero">
                                                <span>Transporte</span>
                                                <span class="category-amount">€150</span>
                                            </div>
                                            <div class="category-item-hero">
                                                <span>Entretenimento</span>
                                                <span class="category-amount">€100</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hero-card hero-card-3">
                                        <div class="card-header">
                                            <svg class="icon card-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M12 1C8.1 1 5 4.1 5 8c0 .7.1 1.4.3 2H4v6c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-6h-1.3c.2-.6.3-1.3.3-2 0-3.9-3.1-7-7-7zm0 2c2.8 0 5 2.2 5 5 0 .6-.1 1.1-.3 1.6L12 9 7.3 10.6C7.1 10.1 7 9.6 7 9c0-2.8 2.2-5 5-5zM6 14v-2h12v2H6z"/></svg>
                                            <span class="card-title">Simulação PPR</span>
                                        </div>
                                        <div class="card-content">
                                            <div class="ppr-simulation">
                                                <div class="sim-item">
                                                    <span>Poupança Mensal</span>
                                                    <span class="sim-value">€500</span>
                                                </div>
                                                <div class="sim-item">
                                                    <span>Retorno Estimado</span>
                                                    <span class="sim-value success">€85,340</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="features-section">
                <div class="container">
                    <div class="section-header">
                        <h2>As suas finanças, simplificadas</h2>
                        <p>Desvalore tudo em funcionalidades que o libertam transferir a sua educação com o clima</p>
                    </div>
                        <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon feature-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M2 10h20" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                            </div>
                            <h3>Controlo de Despesas</h3>
                            <p>Registe e acompanhe todas as despesas com a nossa interface intuitiva. Organize e categorize.</p>
                            <ul class="feature-list">
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Categorização automática</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Relatórios mensais</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Alertas de gastos</li>
                            </ul>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon feature-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M3 17l4-6 4 4 6-10" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <h3>Orçamento Inteligente</h3>
                            <p>Defina limites por categoria e receba notificações quando se aproximar dos limites.</p>
                            <ul class="feature-list">
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Orçamentos personalizados</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Acompanhamento em tempo real</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Sugestões de poupança</li>
                            </ul>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon feature-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="3" y="2" width="18" height="20" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="8" cy="7" r="1" fill="currentColor"/><circle cx="12" cy="7" r="1" fill="currentColor"/><circle cx="16" cy="7" r="1" fill="currentColor"/></svg>
                            </div>
                            <h3>Calculadora PPR</h3>
                            <p>Simule cenários de PPR. Simule diferentes cenários e descubra o potencial dos seus investimentos.</p>
                            <ul class="feature-list">
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Simulações personalizadas</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Cálculo de retorno</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Relatórios de cálculos</li>
                            </ul>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon feature-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M9 18h6v1a1 1 0 0 1-1 1H10a1 1 0 0 1-1-1v-1z" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M12 2a6 6 0 0 0-4 10v2a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-2a6 6 0 0 0-4-10z" fill="none" stroke="currentColor" stroke-width="1.4"/></svg>
                            </div>
                            <h3>Dicas Financeiras</h3>
                            <p>Aceda a conselhos de especialistas e aprenda a tomar melhores decisões financeiras e tomar decisões.</p>
                            <ul class="feature-list">
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Conselhos especializados</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Dicas personalizadas</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Planos de consultoria</li>
                            </ul>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon feature-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M3 12h3v6H3zM9 8h3v10H9zM15 4h3v14h-3z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <h3>Histórico Financeiro</h3>
                            <p>Analise seu padrão de gastos ao longo do tempo e identifique oportunidades de poupança com os seus.</p>
                            <ul class="feature-list">
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Análise temporal</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Comparação de períodos</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Exportação de dados</li>
                            </ul>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon feature-icon-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M8 10V8a4 4 0 0 1 8 0v2" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            </div>
                            <h3>Acesso em Qualquer Lugar</h3>
                            <p>Aceda a suas finanças em qualquer lugar. Sincronizado com todos os seus dispositivos. Experiencie segurança.</p>
                            <ul class="feature-list">
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Design responsivo</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Sincronização multi-dispositivo</li>
                                <li><svg class="icon list-check" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg> Notificações push</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Why Choose Section -->
            <section class="why-choose-section">
                <div class="container">
                    <div class="why-choose-content">
                        <div class="why-choose-text">
                            <h2>Porquê escolher o BillWise?</h2>
                            <p>Uma solução moderna para gerir as suas finanças de forma eficiente e segura.</p>
                            
                            <div class="why-choose-list">
                                <div class="why-item">
                                    <div class="why-icon">
                                        <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>
                                    </div>
                                    <div class="why-text">
                                        <h4>Segurança e Privacidade</h4>
                                        <p>Os seus dados estão sempre protegidos com encriptação de nível bancário e conformidade total.</p>
                                    </div>
                                </div>
                                <div class="why-item">
                                    <div class="why-icon">
                                        <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>
                                    </div>
                                    <div class="why-text">
                                        <h4>Interface Intuitiva</h4>
                                        <p>Fácil de usar, sem necessidade de conhecimentos técnicos. Começa em minutos.</p>
                                    </div>
                                </div>
                                <div class="why-item">
                                    <div class="why-icon">
                                        <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>
                                    </div>
                                    <div class="why-text">
                                        <h4>Suporte Completo</h4>
                                        <p>Equipa de especialistas pronta para ajudar. Suporte disponível 24/7 sempre que precisa.</p>
                                    </div>
                                </div>
                            </div>

                            <?php if ($panel_open_login): ?>
                                <a href="#" class="btn btn-primary open-login">Comece Agora Mesmo</a>
                            <?php else: ?>
                                <a href="dashboard.php" class="btn btn-primary">Ir para o Painel</a>
                            <?php endif; ?>
                        </div>
                        <div class="why-choose-visual">
                            <div class="circular-visual">
                                <div class="circle-inner">
                                    <svg class="icon circle-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="24" cy="24" r="22" fill="#10b981"/><path d="M24 14c-3 0-5 2-5 5 0 3 2 5 5 5s5-2 5-5c0-3-2-5-5-5zm0 14c-6 0-11 2-11 5v2h22v-2c0-3-5-5-11-5z" fill="#fff"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include 'php/footer.php'; ?>

    <!-- AUTH MODAL -->
    <div id="auth-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="auth-title">Entrar</h2>
                <button class="close-btn" id="auth-close">&times;</button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <form id="login-form" style="display:block;">
                    <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
                    <div class="form-group"><label for="password">Senha</label><input type="password" id="password" name="password" required></div>
                    <div class="form-actions"><button class="btn btn-secondary" type="button" id="show-register">Ainda não tem conta?</button><button class="btn btn-primary" type="submit">Entrar</button></div>
                </form>
                <form id="register-form" style="display:none;">
                    <div class="form-group"><label for="nome">Nome</label><input type="text" id="nome" name="nome" required></div>
                    <div class="form-group"><label for="reg-email">Email</label><input type="email" id="reg-email" name="email" required></div>
                    <div class="form-group"><label for="reg-password">Senha</label><input type="password" id="reg-password" name="password" required></div>
                    <div class="form-actions"><button class="btn btn-secondary" type="button" id="show-login">Já tenho conta</button><button class="btn btn-primary" type="submit">Registar</button></div>
                </form>
            </div>
        </div>
    </div>

    <!-- RESTRICTED ACCESS MODAL -->
    <div id="restricted-modal" class="modal">
        <div class="modal-content restricted-modal-content">
            <button class="close-btn" id="restricted-close">&times;</button>
            <div class="modal-body">
                <div class="restricted-content">
                    <div class="restricted-icon">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="40" fill="#FEF3C7"/>
                            <path d="M40 20C32.268 20 26 26.268 26 34v4h-2a4 4 0 0 0-4 4v18a4 4 0 0 0 4 4h32a4 4 0 0 0 4-4V42a4 4 0 0 0-4-4h-2v-4c0-7.732-6.268-14-14-14zm0 4c5.514 0 10 4.486 10 10v4H30v-4c0-5.514 4.486-10 10-10zm0 20a4 4 0 1 1 0 8 4 4 0 0 1 0-8z" fill="#F59E0B"/>
                        </svg>
                    </div>
                    <h3>Conteúdo Exclusivo para Membros</h3>
                    <p>Para aceder a esta funcionalidade e gerir as suas finanças, precisa de ter uma conta BillWise.</p>
                    <div class="restricted-benefits">
                        <div class="benefit-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 10l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Gestão completa de despesas</span>
                        </div>
                        <div class="benefit-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 10l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Orçamentos personalizados</span>
                        </div>
                        <div class="benefit-item">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 10l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Simulador de investimentos</span>
                        </div>
                    </div>
                    <div class="restricted-actions">
                        <button class="btn btn-primary" id="restricted-login">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 11a4 4 0 100-8 4 4 0 000 8zM3 18a7 7 0 0114 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Fazer Login
                        </button>
                        <button class="btn btn-secondary" id="restricted-register">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 5v10m-5-5h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Criar Conta Grátis
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/notifications.js?v=1"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
