<?php
header('Content-Type: text/html; charset=UTF-8');

// Página de Definições da Conta
// Permite ao utilizador editar perfil, alterar password e eliminar conta
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'php/config.php';

$user_id = $_SESSION['user_id'];

// Carregar dados atuais do utilizador da base de dados
try {
    $stmt = $pdo->prepare("SELECT nome, email FROM utilizadores WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Se o utilizador não existe, fazer logout
    if (!$user) {
        header('Location: php/logout.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao carregar dados do utilizador");
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definições - BillWise</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/sidebar.css?v=1">
    <link rel="stylesheet" href="assets/css/buttons.css?v=4">
    <link rel="stylesheet" href="assets/css/forms.css?v=3">
    <link rel="stylesheet" href="assets/css/modals.css?v=4">
    <link rel="stylesheet" href="assets/css/dashboard.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .btn-primary {
            background: var(--primary-gradient) !important;
            color: white !important;
            padding: 12px 24px !important;
            border-radius: 8px !important;
            border: none !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            display: inline-block !important;
            font-size: 16px !important;
        }
        .btn-danger {
            background: var(--danger) !important;
            color: white !important;
            padding: 12px 24px !important;
            border-radius: 8px !important;
            border: none !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            display: inline-block !important;
            font-size: 16px !important;
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/sidebar.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container">
    
    <div class="dashboard-hero">
        <div class="hero-greeting">
            <h1>Definições da Conta</h1>
            <p>Gerir as informações da sua conta BillWise</p>
        </div>
    </div>
    
    <div class="settings-container">
        <div id="success-msg" class="success-message"></div>
        <div id="error-msg" class="error-message"></div>
        
        <!-- Informações Pessoais -->
        <div class="settings-section">
            <h2>Informações Pessoais</h2>
            <form id="profile-form">
                <div class="form-row">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
                </div>
                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Alterações</button>
            </form>
        </div>
        
        <!-- Alterar Senha -->
        <div class="settings-section">
            <h2>Segurança</h2>
            <form id="password-form">
                <div class="form-row">
                    <label for="senha-atual">Senha Atual</label>
                    <input type="password" id="senha-atual" name="senha_atual" required>
                </div>
                <div class="form-row">
                    <label for="nova-senha">Nova Senha</label>
                    <input type="password" id="nova-senha" name="nova_senha" required minlength="6">
                </div>
                <div class="form-row">
                    <label for="confirmar-senha">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar-senha" name="confirmar_senha" required minlength="6">
                </div>
                <button type="submit" class="btn btn-primary">Alterar Senha</button>
            </form>
        </div>
        
        <!-- Zona de Perigo -->
        <div class="settings-section danger-zone">
            <h2>Zona de Perigo</h2>
            <p class="danger-text">
                Esta ação é irreversível. Todos os seus dados (despesas e orçamentos) serão permanentemente eliminados.
            </p>
            <button type="button" class="btn-danger" onclick="confirmarEliminacao()">
                Eliminar Conta
            </button>
        </div>
    </div>
    
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/main.js?v=3"></script>
    <script src="assets/js/notifications.js?v=1"></script>
    <script>
        // Atualizar perfil
        document.getElementById('profile-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = {
                nome: formData.get('nome'),
                email: formData.get('email')
            };
            
            try {
                const response = await fetch('php/update_profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Notifications.success('Perfil atualizado com sucesso!');
                } else {
                    Notifications.error(result.error || 'Erro ao atualizar perfil');
                }
            } catch (error) {
                Notifications.error('Erro ao comunicar com o servidor');
            }
        });
        
        // Alterar senha
        document.getElementById('password-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const novaSenha = formData.get('nova_senha');
            const confirmarSenha = formData.get('confirmar_senha');
            
            if (novaSenha !== confirmarSenha) {
                Notifications.error('As senhas não coincidem');
                return;
            }
            
            const data = {
                senha_atual: formData.get('senha_atual'),
                nova_senha: novaSenha
            };
            
            try {
                const response = await fetch('php/update_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Notifications.success('Senha alterada com sucesso!');
                    e.target.reset();
                } else {
                    Notifications.error(result.error || 'Erro ao alterar senha');
                }
            } catch (error) {
                Notifications.error('Erro ao comunicar com o servidor');
            }
        });
        
        function confirmarEliminacao() {
            ConfirmModal.show({
                title: 'ATENÇÃO',
                message: 'Tem a certeza que deseja eliminar a sua conta?\n\nEsta ação é IRREVERSÍVEL e todos os seus dados serão permanentemente apagados:\n\n• Todas as despesas\n• Todos os orçamentos',
                icon: '',
                iconClass: 'danger',
                confirmText: 'Eliminar Conta',
                cancelText: 'Cancelar',
                onConfirm: () => {
                    // Segunda confirmação
                    const confirmacaoFinal = prompt('Digite "ELIMINAR" em maiúsculas para confirmar:');
                    if (confirmacaoFinal === 'ELIMINAR') {
                        eliminarConta();
                    } else {
                        Notifications.warning('Eliminação cancelada');
                    }
                }
            });
        }
        
        async function eliminarConta() {
            try {
                const response = await fetch('php/delete_account.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Notifications.success('Conta eliminada com sucesso. Será redirecionado para a página inicial.');
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    Notifications.error(result.error || 'Erro ao eliminar conta');
                }
            } catch (error) {
                Notifications.error('Erro ao comunicar com o servidor');
            }
        }
    </script>
</body>
</html>

