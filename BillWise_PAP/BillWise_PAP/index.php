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
header('Location: painel.php');
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
    <link rel="stylesheet" href="assets/css/modals.css?v=3">
    <link rel="stylesheet" href="assets/css/footer.css?v=2">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php

include 'php/header.php'; ?>
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
                                    <?php

if ($panel_open_login): ?>
                                        <a href="#" class="btn btn-primary open-login">Começar Agora</a>
                                    <?php

else: ?>
                                        <a href="<?php

echo $panel_link; ?>" class="btn btn-primary">Ir para o Painel</a>
                                    <?php

endif; ?>
                                    <?php

if ($panel_open_login): ?>
                                        <a href="#" class="btn btn-outline open-restricted">Aprender Mais</a>
                                    <?php

else: ?>
                                        <a href="orcamento.php" class="btn btn-outline">Aprender Mais</a>
                                    <?php

endif; ?>
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

                            <?php

if ($panel_open_login): ?>
                                <a href="#" class="btn btn-primary open-login">Comece Agora Mesmo</a>
                            <?php

else: ?>
href="painel.php" class="btn btn-primary">Ir para o Painel
                            <?php

endif; ?>
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

    <?php

include 'php/footer.php'; ?>

    <!-- AUTH MODAL -->
    <div id="auth-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="auth-title">Entrar</h2>
                <button class="close-btn" id="auth-close">&times;</button>
            </div>
            <!-- Modal Login (Default) -->
            <div class="modal-body" style="padding:1.5rem;">
                <form id="login-form">
                    <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
                    <div class="form-group"><label for="password">Senha</label><input type="password" id="password" name="password" required></div>
                    <div class="form-group" style="margin-bottom: 10px;">
                        <button type="button" id="show-recover" style="background: transparent !important; border: none !important; color: #dc2626 !important; font-size: 14px !important; font-weight: 600 !important; text-decoration: underline !important; cursor: pointer !important; padding: 8px 12px !important; display: inline-block !important; width: auto !important;">Esqueci a password</button>
                    </div>
                    <div class="form-actions" style="margin-top: 5px;">
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: flex-start !important;">
                            <button class="btn btn-secondary" type="button" id="show-register">Ainda não tem conta?</button>
                            <button class="btn btn-primary" type="submit">Entrar</button>
                        </div>
                    </div>
                </form>
                <!-- Modal de Registro (Escondido) -->
                <form id="register-form" class="auth-form" style="display:none;">
                    <div class="form-group"><label for="nome">Nome</label><input type="text" id="nome" name="nome" required></div>
                    <div class="form-group"><label for="reg-email">Email</label><input type="email" id="reg-email" name="email" required></div>
                    <div class="form-group"><label for="reg-password">Senha</label><input type="password" id="reg-password" name="password" required></div>
                    <div class="form-actions"><button class="btn btn-secondary" type="button" id="show-login">Já tenho conta</button><button class="btn btn-primary" type="submit">Registar</button></div>
                </form>

                <!-- Password Recovery - Step 1: Email -->
                <form id="recover-email-form" class="auth-form" style="display:none;">
                    <p style="margin-bottom: 1rem; color: var(--gray-600); font-size: 0.9rem;">Introduza o seu email para receber um código de recuperação.</p>
                    <div class="form-group"><label for="recover-email">Email</label><input type="email" id="recover-email" name="email" required placeholder="Introduza o seu email"></div>
                    <div class="form-actions">
                        <button class="btn btn-secondary" type="button" id="back-to-login">Voltar ao Login</button>
                        <button class="btn btn-primary" type="submit">Enviar Código</button>
                    </div>
                </form>

                <!-- Password Recovery - Step 2: Code + New Password -->
<form id="recover-code-form" class="auth-form" style="display:none;">

                    <p style="margin-bottom: 1rem; color: var(--gray-600); font-size: 0.9rem;">Introduza o código de 6 dígitos enviado para o seu email.</p>
                    <div class="form-group">
                        <label>Código</label>
                        <div class="code-input-group" style="display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 0.5rem;">
                            <input type="text" class="code-input-modal" maxlength="1" data-index="0" required style="width: 40px; height: 45px; text-align: center; font-size: 1.25rem; font-weight: bold; border: 2px solid var(--gray-300); border-radius: 8px;">
                            <input type="text" class="code-input-modal" maxlength="1" data-index="1" required style="width: 40px; height: 45px; text-align: center; font-size: 1.25rem; font-weight: bold; border: 2px solid var(--gray-300); border-radius: 8px;">
                            <input type="text" class="code-input-modal" maxlength="1" data-index="2" required style="width: 40px; height: 45px; text-align: center; font-size: 1.25rem; font-weight: bold; border: 2px solid var(--gray-300); border-radius: 8px;">
                            <input type="text" class="code-input-modal" maxlength="1" data-index="3" required style="width: 40px; height: 45px; text-align: center; font-size: 1.25rem; font-weight: bold; border: 2px solid var(--gray-300); border-radius: 8px;">
                            <input type="text" class="code-input-modal" maxlength="1" data-index="4" required style="width: 40px; height: 45px; text-align: center; font-size: 1.25rem; font-weight: bold; border: 2px solid var(--gray-300); border-radius: 8px;">
                            <input type="text" class="code-input-modal" maxlength="1" data-index="5" required style="width: 40px; height: 45px; text-align: center; font-size: 1.25rem; font-weight: bold; border: 2px solid var(--gray-300); border-radius: 8px;">
                        </div>
                        <input type="hidden" id="recover-codigo" name="codigo">
                    </div>
                    <div class="form-group"><label for="recover-nova-senha">Nova Password</label><input type="password" id="recover-nova-senha" name="nova_senha" required minlength="6" placeholder="Mínimo 6 caracteres"></div>
                    <div class="form-group"><label for="recover-confirmar-senha">Confirmar Password</label><input type="password" id="recover-confirmar-senha" name="confirmar_senha" required minlength="6" placeholder="Confirme a password"></div>
                    <div class="form-actions">
                        <button class="btn btn-secondary" type="button" id="back-to-recover-email">Voltar</button>
                        <button class="btn btn-primary" type="button" id="submit-recover-code">Alterar Password</button>
                    </div>
                </div>

                <!-- Password Recovery - Step 3: Success -->
                <div id="recover-success" class="auth-form" style="display:none; text-align: center; padding: 1rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 style="margin-bottom: 0.5rem; color: var(--gray-900);">Password Alterada!</h3>
                    <p style="margin-bottom: 1.5rem; color: var(--gray-600); font-size: 0.9rem;">A sua password foi alterada com sucesso.</p>
                    <button class="btn btn-primary" type="button" id="go-to-login" style="width: 100%;">Voltar ao Login</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        // navegação das tabs e acordeão FAQ
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const sections = document.querySelectorAll('.page-section');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // remover classe ativa de todos os botões e resetar estilos
                    tabBtns.forEach(b => {
                        b.classList.remove('active');
                        b.style.color = '#64748b';
                        b.style.borderBottom = 'none';
                    });
                    // adicionar classe ativa ao botão clicado e aplicar estilos
                    btn.classList.add('active');
                    btn.style.color = '#2563eb';
                    btn.style.borderBottom = '2px solid #2563eb';

                    // esconder todas as seções
                    sections.forEach(section => section.classList.remove('active'));

                    // mostrar a seção correspondente ao botão clicado
                    const tabId = btn.getAttribute('data-tab');
                    const targetSection = document.getElementById(tabId);
                    if (targetSection) {
                        targetSection.classList.add('active');
                    }
                });
            });

            // Acordeão FAQ
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const answer = question.nextElementSibling;
                    const isOpen = answer.style.display === 'block';
                    // Fechar todas as respostas
                    document.querySelectorAll('.faq-answer').forEach(ans => ans.style.display = 'none');
                    // Abrir a resposta da pergunta clicada se não estiver aberta
                    if (!isOpen) {
                        answer.style.display = 'block';
                    }
                });
            });
        });
    </script>

    <script src="assets/js/notifications.js?v=1"></script>
<script>
function handleCodeSubmit() {
  console.log('BUTTON CLICKED!');
  const codigo = document.getElementById('recover-codigo').value;
  const novaSenha = document.getElementById('recover-nova-senha').value;
  const confirmarSenha = document.getElementById('recover-confirmar-senha').value;
  const recoverEmail = window.recoverEmailGlobal || '';
  
  console.log('SUBMIT DATA:', {codigo, novaSenha: novaSenha.length, email: recoverEmail});
  
  if (codigo.length !== 6) {
    alert('Código deve ter 6 dígitos!');
    return;
  }
  if (novaSenha !== confirmarSenha) {
    alert('Passwords não coincidem!');
    return;
  }
  if (novaSenha.length < 6) {
    alert('Password mínimo 6 chars!');
    return;
  }
  
  fetch('php/redefinir_password.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    credentials: 'same-origin',
    body: JSON.stringify({email: recoverEmail, codigo, nova_senha: novaSenha})
  })
  .then(r => r.json())
  .then(result => {
    console.log('RESPONSE:', result);
    if (result.success) {
      document.getElementById('recover-code-form').style.display = 'none';
      document.getElementById('recover-success').style.display = 'block';
    } else {
      alert('Erro: ' + result.error);
    }
  })
  .catch(e => {
    console.error(e);
    alert('Erro de rede: ' + e.message);
  });
}
</script>
<script src="assets/js/notifications.js?v=1"></script>
<script src="assets/js/main.js?v=3"></script>
</body>
</html>



