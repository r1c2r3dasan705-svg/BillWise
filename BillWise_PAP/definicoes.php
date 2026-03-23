<?php
require_once 'php/base.php';

billwise_require_auth();
require_once 'php/configuracao.php';

$stmt = $pdo->prepare('SELECT nome, email FROM utilizadores WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: php/sair.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definições - BillWise</title>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <link rel="stylesheet" href="assets/css/botoes.css?v=4">
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
                            <h1>Definições da Conta</h1>
                            <p>Atualize os seus dados pessoais, segurança e opções de conta.</p>
                        </div>
                    </section>

                    <div class="settings-container">
                        <div class="settings-section">
                            <h2>Informações Pessoais</h2>
                            <form id="profile-form">
                                <div class="form-row">
                                    <label for="nome">Nome Completo</label>
                                    <input type="text" id="nome" name="nome" value="<?php echo billwise_escape($user['nome']); ?>" required>
                                </div>
                                <div class="form-row">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="<?php echo billwise_escape($user['email']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                            </form>
                        </div>

                        <div class="settings-section">
                            <h2>Segurança</h2>
                            <form id="password-form">
                                <div class="form-row">
                                    <label for="senha-atual">Password Atual</label>
                                    <input type="password" id="senha-atual" name="senha_atual" required>
                                </div>
                                <div class="form-row">
                                    <label for="nova-senha">Nova Password</label>
                                    <input type="password" id="nova-senha" name="nova_senha" required minlength="6">
                                </div>
                                <div class="form-row">
                                    <label for="confirmar-senha">Confirmar Nova Password</label>
                                    <input type="password" id="confirmar-senha" name="confirmar_senha" required minlength="6">
                                </div>
                                <button type="submit" class="btn btn-primary">Alterar Password</button>
                            </form>
                        </div>

                        <div class="settings-section danger-zone">
                            <h2>Zona de Perigo</h2>
                            <p class="danger-text">Ao eliminar a conta, todos os dados financeiros e conteúdo do fórum associados serão removidos.</p>
                            <button type="button" class="btn btn-danger" onclick="confirmarEliminacao()">Eliminar Conta</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/notificacoes.js?v=1"></script>
    <script>
    document.getElementById('profile-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        const response = await fetch('php/atualizar_perfil.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nome: formData.get('nome'),
                email: formData.get('email'),
            }),
        });

        const result = await response.json();

        if (result.success) {
            Notifications.success('Perfil atualizado com sucesso.');
            return;
        }

        Notifications.error(result.error || 'Erro ao atualizar perfil.');
    });

    document.getElementById('password-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        const novaSenha = formData.get('nova_senha');
        const confirmarSenha = formData.get('confirmar_senha');

        if (novaSenha !== confirmarSenha) {
            Notifications.error('As passwords não coincidem.');
            return;
        }

        const response = await fetch('php/atualizar_palavra_passe.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                senha_atual: formData.get('senha_atual'),
                nova_senha: novaSenha,
            }),
        });

        const result = await response.json();

        if (result.success) {
            Notifications.success('Password alterada com sucesso.');
            event.target.reset();
            return;
        }

        Notifications.error(result.error || 'Erro ao alterar password.');
    });

    function confirmarEliminacao() {
        ConfirmModal.show({
            title: 'Eliminar Conta',
            message: 'Esta ação é irreversível. Todos os seus dados serão removidos.',
            iconClass: 'danger',
            confirmText: 'Eliminar Conta',
            cancelText: 'Cancelar',
            onConfirm: () => {
                const confirmacaoFinal = prompt('Digite "ELIMINAR" para confirmar.');
                if (confirmacaoFinal === 'ELIMINAR') {
                    eliminarConta();
                    return;
                }

                Notifications.warning('Eliminação cancelada.');
            }
        });
    }

    async function eliminarConta() {
        const response = await fetch('php/apagar_conta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
        });

        const result = await response.json();

        if (result.success) {
            Notifications.success('Conta eliminada com sucesso. Será redirecionado.');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1500);
            return;
        }

        Notifications.error(result.error || 'Erro ao eliminar conta.');
    }
    </script>
</body>
</html>


