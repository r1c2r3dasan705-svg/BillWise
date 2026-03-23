<?php
require_once __DIR__ . '/base.php';

billwise_start_session();

$logged = !empty($_SESSION['user_id']);
$user_name = $logged ? billwise_user_name() : '';
?>
<a class="skip-link" href="#main">Saltar para o conteúdo</a>
<header class="header">
    <div class="container">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <span>BillWise</span>
            </a>

            <button class="menu-btn" type="button" aria-label="Abrir menu">
                Menu
            </button>

            <nav class="nav">
                <a href="index.php" class="nav-link">Início</a>
                <a href="sobre.php" class="nav-link">Sobre Nós</a>
                <a href="perguntas_frequentes.php" class="nav-link">FAQ</a>
                <?php if ($logged): ?>
                    <a href="painel.php" class="nav-link">Painel</a>
                    <a href="despesas.php" class="nav-link">Despesas</a>
                    <a href="orcamento.php" class="nav-link">Orçamento</a>
                    <a href="forum.php" class="nav-link">Fórum</a>
                <?php endif; ?>
            </nav>

            <div class="user-menu">
                <?php if ($logged): ?>
                    <span style="margin-right: 0.75rem; color: var(--gray-700);">Olá, <?php echo $user_name; ?></span>
                    <a href="definicoes.php" class="btn btn-outline" style="margin-right: 0.5rem;">Definições</a>
                    <a class="btn btn-secondary" href="php/sair.php">Sair</a>
                <?php else: ?>
                    <button class="user-btn open-login" type="button">Entrar / Registar</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<?php if (!$logged): ?>
<div id="auth-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="auth-title">Entrar</h2>
            <button class="close-btn" id="auth-close" type="button">&times;</button>
        </div>
        <div class="modal-body" style="padding: 1.5rem;">
            <form id="login-form" style="display: block;">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group" style="margin-bottom: 10px;">
                    <button type="button" id="show-recover" style="background: transparent; border: none; color: #dc2626; font-size: 14px; font-weight: 600; text-decoration: underline; cursor: pointer; padding: 0;">
                        Esqueci a password
                    </button>
                </div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="button" id="show-register">Ainda não tem conta?</button>
                    <button class="btn btn-primary" type="submit">Entrar</button>
                </div>
            </form>

            <form id="register-form" style="display: none;">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="button" id="show-login">Já tenho conta</button>
                    <button class="btn btn-primary" type="submit">Registar</button>
                </div>
            </form>

            <form id="recover-email-form" class="auth-form" style="display: none;">
                <p style="margin-bottom: 1rem; color: var(--gray-600); font-size: 0.9rem;">Introduza o seu email para receber um código de recuperação.</p>
                <div class="form-group">
                    <label for="recover-email">Email</label>
                    <input type="email" id="recover-email" name="email" required placeholder="Introduza o seu email">
                </div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="button" id="back-to-login">Voltar ao login</button>
                    <button class="btn btn-primary" type="submit">Enviar código</button>
                </div>
            </form>

            <div id="recover-code-form" class="auth-form" style="display: none;">
                <p style="margin-bottom: 1rem; color: var(--gray-600); font-size: 0.9rem;">Introduza o código de 6 dígitos enviado para o seu email.</p>
                <div class="form-group">
                    <label>Código</label>
                    <div class="code-input-group" style="display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 0.5rem;">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <input type="text" class="code-input-modal" maxlength="1" data-index="<?php echo $i; ?>" required style="width: 40px; height: 45px; text-align: center; font-size: 1.25rem; font-weight: bold; border: 2px solid var(--gray-300); border-radius: 8px;">
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" id="recover-codigo" name="codigo">
                </div>
                <div class="form-group">
                    <label for="recover-nova-senha">Nova Password</label>
                    <input type="password" id="recover-nova-senha" name="nova_senha" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="recover-confirmar-senha">Confirmar Password</label>
                    <input type="password" id="recover-confirmar-senha" name="confirmar_senha" required minlength="6">
                </div>
                <div class="form-actions">
                    <button class="btn btn-secondary" type="button" id="back-to-recover-email">Voltar</button>
                    <button class="btn btn-primary" type="button" id="submit-recover-code" onclick="handleCodeSubmit()">Alterar Password</button>
                </div>
            </div>

            <div id="recover-success" class="auth-form" style="display: none; text-align: center; padding: 1rem;">
                <h3 style="margin-bottom: 0.5rem; color: var(--gray-900);">Password alterada</h3>
                <p style="margin-bottom: 1.5rem; color: var(--gray-600); font-size: 0.9rem;">A sua password foi alterada com sucesso.</p>
                <button class="btn btn-primary" type="button" id="go-to-login" style="width: 100%;">Voltar ao login</button>
            </div>
        </div>
    </div>
</div>

<div id="restricted-modal" class="modal">
    <div class="modal-content restricted-modal-content">
        <button class="close-btn" id="restricted-close" type="button">&times;</button>
        <div class="modal-body">
            <div class="restricted-content">
                <h3>Conteúdo exclusivo para membros</h3>
                <p>Para aceder a esta funcionalidade precisa de ter uma conta BillWise.</p>
                <div class="restricted-benefits">
                    <div class="benefit-item"><span>Gestão completa de despesas</span></div>
                    <div class="benefit-item"><span>Orçamentos personalizados</span></div>
                    <div class="benefit-item"><span>Simuladores financeiros</span></div>
                </div>
                <div class="restricted-actions">
                    <button class="btn btn-primary" id="restricted-login" type="button">Fazer Login</button>
                    <button class="btn btn-secondary" id="restricted-register" type="button">Criar Conta</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function handleCodeSubmit() {
    const codigo = document.getElementById('recover-codigo')?.value || '';
    const novaSenha = document.getElementById('recover-nova-senha')?.value || '';
    const confirmarSenha = document.getElementById('recover-confirmar-senha')?.value || '';
    const recoverEmail = window.recoverEmailGlobal || '';

    if (codigo.length !== 6) {
        if (window.Notifications) {
            Notifications.error('O código deve ter 6 dígitos.');
        }
        return;
    }

    if (novaSenha.length < 6) {
        if (window.Notifications) {
            Notifications.error('A password deve ter pelo menos 6 caracteres.');
        }
        return;
    }

    if (novaSenha !== confirmarSenha) {
        if (window.Notifications) {
            Notifications.error('As passwords não coincidem.');
        }
        return;
    }

    fetch('php/redefinir_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify({ email: recoverEmail, codigo, nova_senha: novaSenha })
    })
    .then((response) => response.json())
    .then((result) => {
        if (result.success) {
            document.getElementById('recover-code-form').style.display = 'none';
            document.getElementById('recover-success').style.display = 'block';
            return;
        }

        if (window.Notifications) {
            Notifications.error(result.error || 'Não foi possível alterar a password.');
        }
    })
    .catch(() => {
        if (window.Notifications) {
            Notifications.error('Erro de comunicação com o servidor.');
        }
    });
}
</script>
<?php endif; ?>

