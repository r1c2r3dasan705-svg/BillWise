<?php
// Componente de cabeçalho - navegação principal e modal de autenticação
// Garante que a sessão está ativa antes de tentar aceder a $_SESSION
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
            <nav class="nav" style="display: flex !important;">
                <a href="index.php" class="nav-link">Início</a>
                <a href="sobre.php" class="nav-link">Sobre Nós</a>
                <a href="faq.php" class="nav-link">FAQ</a>
                <?php if ($logged): ?>
href="painel.php" class="nav-link">Painel
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
                <div class="form-group" style="margin-bottom: 10px;">
                    <button type="button" id="show-recover" style="background: transparent !important; border: none !important; color: #dc2626 !important; font-size: 14px !important; font-weight: 600 !important; text-decoration: underline !important; cursor: pointer !important; padding: 8px 12px !important; display: inline-block !important; width: auto !important;">Esqueci a password</button>
                </div>
                <div class="form-actions"><button class="btn btn-secondary" type="button" id="show-register">Ainda não tem conta?</button><button class="btn btn-primary" type="submit">Entrar</button></div>
            </form>
            <form id="register-form" style="display:none;">
                <div class="form-group"><label for="nome">Nome</label><input type="text" id="nome" name="nome" required></div>
                <div class="form-group"><label for="reg-email">Email</label><input type="email" id="reg-email" name="email" required></div>
                <div class="form-group"><label for="reg-password">Senha</label><input type="password" id="reg-password" name="password" required></div>
                <div class="form-actions"><button class="btn btn-secondary" type="button" id="show-login">Já tenho conta</button><button class="btn btn-primary" type="submit">Registar</button></div>
            </form>
            
            <!-- Recuperação de password - Passo 1: solicitar email -->
            <form id="recover-email-form" class="auth-form" style="display:none;">
                <p style="margin-bottom: 1rem; color: var(--gray-600); font-size: 0.9rem;">Introduza o seu email para receber um código de recuperação.</p>
                <div class="form-group"><label for="recover-email">Email</label><input type="email" id="recover-email" name="email" required placeholder="Introduza o seu email"></div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="button" id="back-to-login">Voltar ao Login</button>
                    <button class="btn btn-primary" type="submit">Enviar Código</button>
                </div>
            </form>

            <!-- Recuperação de password - Passo 2: validar código e nova senha -->
            <div id="recover-code-form" class="auth-form" style="display:none;">
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

            <!-- Mensagem de sucesso após alteração de password -->
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

<!-- Modal De Acesso Restrito -->
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


