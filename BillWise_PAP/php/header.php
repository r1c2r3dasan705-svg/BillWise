<?php
// Componente de cabeçalho - navegação principal e modal de autenticação
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verificar se utilizador está autenticado
$logged = isset($_SESSION['user_id']);
$user_name = $logged ? htmlentities($_SESSION['name']) : null;
?>
<a class="skip-link" href="#main">Saltar para o conteúdo</a>
<header class="header">
    <div class="container">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <span>BillWise</span>
            </a>
            <button class="menu-btn" aria-label="Open menu">Menu</button>
            <nav class="nav">
                <a href="index.php" class="nav-link">Início</a>
                <?php if ($logged): ?>
                    <!-- Links disponíveis apenas para utilizadores autenticados -->
                    <a href="dashboard.php" class="nav-link">Painel</a>
                    <a href="despesas.php" class="nav-link">Despesas</a>
                    <a href="orcamento.php" class="nav-link">Orçamento</a>
                    <a href="calculadora_ppr.php" class="nav-link">Calculadora</a>
                <?php endif; ?>
            </nav>
            <div class="user-menu">
                <?php if ($logged): ?>
                    <a href="definicoes.php" class="btn btn-outline" style="margin-right:0.5rem;" title="Definições da Conta">Definições</a>
                    <span style="margin-right:0.5rem; color:var(--gray-700);">Olá, <?php echo $user_name; ?></span>
                    <a class="btn btn-secondary" href="php/logout.php">Sair</a>
                <?php else: ?>
                    <button class="user-btn open-login">Entrar / Registar</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- MODAL DE AUTENTICAÇÃO (Login e Registo) -->
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
