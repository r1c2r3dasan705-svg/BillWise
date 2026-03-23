<?php
header('Content-Type: text/html; charset=UTF-8');
// Verificar autenticação do utilizador
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Preparar dados do utilizador para pré-preencher o formulário
$user_name = htmlentities($_SESSION['name']);
$logged = true;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - BillWise</title>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <link rel="stylesheet" href="assets/css/botoes.css?v=3">
    <link rel="stylesheet" href="assets/css/formularios.css?v=3">
    <link rel="stylesheet" href="assets/css/modais.css?v=4">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/barra_lateral.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container">
                    <!-- Hero Section -->
                    <?php
if (isset($_SESSION['feedback_success'])) {
    echo '<div id="success-msg" class="success-message show">';
    echo '<h2>Feedback enviado com sucesso</h2>';
    echo '<p>Obrigado pela sua mensagem. Iremos analisar e responder em breve.</p>';
    echo '</div>';
    unset($_SESSION['feedback_success']);
}
?>
                    <section class="page-section active">
                        <!-- Hero Section -->
                        <section class="dashboard-hero" style="padding: 1.5rem 0; margin-bottom: 1.5rem;">
                            <div class="hero-greeting">
                                <h1 style="font-size: 1.75rem; margin-bottom: 0.25rem;">Feedback</h1>
                                <p style="font-size: 0.95rem;">Ajude-nos a melhorar partilhando a sua opinião</p>
                            </div>
                        </section>
                        
                        <div class="feedback-card" id="feedback-form-container" style="max-width: 1200px; margin: 0 auto; background: white; border-radius: 16px; padding: 2.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h2 style="margin-bottom: 1.5rem;">Como podemos ajudar?</h2>
                            
                            <form id="feedback-form">
                                <!-- Tipo de Feedback -->
                                <div class="feedback-types" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                                    <div class="type-option" style="position: relative;">
                                        <input type="radio" id="type-sugestao" name="tipo" value="sugestao" checked style="position: absolute; opacity: 0;">
                                        <label for="type-sugestao" class="type-label" style="display: flex; flex-direction: column; align-items: center; padding: 1.85rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-align: center; min-height: 140px;">
                                                                                        <strong>Sugestão</strong>
                                        </label>
                                    </div>
                                    <div class="type-option" style="position: relative;">
                                        <input type="radio" id="type-reclamacao" name="tipo" value="reclamacao" style="position: absolute; opacity: 0;">
                                        <label for="type-reclamacao" class="type-label" style="display: flex; flex-direction: column; align-items: center; padding: 1.85rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-align: center; min-height: 140px;">
                                                                                        <strong>Reclamação</strong>
                                        </label>
                                    </div>
                                    <div class="type-option" style="position: relative;">
                                        <input type="radio" id="type-elogio" name="tipo" value="elogio" style="position: absolute; opacity: 0;">
                                        <label for="type-elogio" class="type-label" style="display: flex; flex-direction: column; align-items: center; padding: 1.85rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-align: center; min-height: 140px;">
                                                                                        <strong>Elogio</strong>
                                        </label>
                                    </div>
                                    <div class="type-option" style="position: relative;">
                                        <input type="radio" id="type-bug" name="tipo" value="bug" style="position: absolute; opacity: 0;">
                                        <label for="type-bug" class="type-label" style="display: flex; flex-direction: column; align-items: center; padding: 1.85rem 1rem; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.3s; text-align: center; min-height: 140px;">
                                                                                        <strong>Bug</strong>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Nome -->
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" id="nome" name="nome" 
                                           value="<?php echo $logged ? htmlentities($_SESSION['name']) : ''; ?>" 
                                           required>
                                </div>
                                
                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" 
                                           value="<?php echo $logged && isset($_SESSION['email']) ? htmlentities($_SESSION['email']) : ''; ?>" 
                                           required>
                                </div>
                                
                                <!-- Assunto -->
                                <div class="form-group">
                                    <label for="assunto">Assunto</label>
                                    <input type="text" id="assunto" name="assunto" 
                                           placeholder="Resuma o tema da sua mensagem" required>
                                </div>
                                
                                <!-- Mensagem -->
                                <div class="form-group">
                                    <label for="mensagem">Mensagem</label>
                                    <textarea id="mensagem" name="mensagem" rows="6" 
                                              placeholder="Descreva em detalhe o seu feedback..." required></textarea>
                                </div>
                                
                                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Enviar Feedback
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
    
    <script src="assets/js/principal.js?v=3"></script>
    <script src="assets/js/notificacoes.js?v=1"></script>
    <script>
        document.getElementById('feedback-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = {
                nome: formData.get('nome'),
                email: formData.get('email'),
                tipo: formData.get('tipo'),
                assunto: formData.get('assunto'),
                mensagem: formData.get('mensagem')
            };
            
            try {
                const response = await fetch('php/enviar_feedback.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('feedback-form-container').style.display = 'none';
                    if (result.email_sent === false) {
                        Notifications.warning(result.message || 'Feedback guardado, mas o email nao foi enviado.');
                    } else {
                        Notifications.success('Feedback enviado com sucesso! Obrigado pela sua contribuicao.');
                    }
                    
                    // Redirecionar após 3 segundos
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 3000);
                } else {
                    Notifications.error(result.error || 'Erro desconhecido');
                }
            } catch (error) {
                console.error('Erro:', error);
                Notifications.error('Erro ao enviar feedback. Tente novamente.');
            }
        });
    </script>
        </div>
    </div>
</body>
</html>









