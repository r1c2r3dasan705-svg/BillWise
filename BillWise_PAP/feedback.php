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
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/sidebar.css?v=1">
    <link rel="stylesheet" href="assets/css/buttons.css?v=3">
    <link rel="stylesheet" href="assets/css/forms.css?v=3">
    <link rel="stylesheet" href="assets/css/modals.css?v=4">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .feedback-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem 0;
            text-align: center;
        }
        
        .feedback-hero h1 {
            font-size: 1.75rem;
            margin-bottom: 0.25rem;
        }
        
        .feedback-hero p {
            font-size: 0.95rem;
            opacity: 0.95;
        }
        
        .feedback-container {
            max-width: 1040px;
            margin: 0 auto 3rem;
            padding: 0 1rem;
        }
        
        .feedback-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
        }
        
        .feedback-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .type-option {
            position: relative;
        }
        
        .type-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        
        .type-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.85rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            min-height: 140px;
        }
        
        .type-option input[type="radio"]:checked + .type-label {
            border-color: var(--primary);
            background: rgba(37, 99, 235, 0.05);
        }
        
        .type-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .type-label:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }
        
        .success-message {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            text-align: center;
            display: none;
        }
        
        .success-message.show {
            display: block;
            animation: slideInDown 0.5s ease;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/sidebar.php'; ?>
        <div class="main-content-wrapper">
    
    <div class="feedback-hero">
        <div class="container">
            <h1>Feedback & Suporte</h1>
            <p>A sua opinião ajuda-nos a melhorar! Partilhe sugestões, reporte problemas ou deixe um elogio.</p>
        </div>
    </div>
    
    <div class="feedback-container">
        <div id="success-msg" class="success-message">
            <h2>Feedback enviado com sucesso</h2>
            <p>Obrigado pela sua mensagem. Iremos analisar e responder em breve.</p>
        </div>
        
        <div class="feedback-card" id="feedback-form-container">
            <h2 style="margin-bottom: 1.5rem;">Como podemos ajudar?</h2>
            
            <form id="feedback-form">
                <!-- Tipo de Feedback -->
                <div class="feedback-types">
                    <div class="type-option">
                        <input type="radio" id="type-sugestao" name="tipo" value="sugestao" checked>
                        <label for="type-sugestao" class="type-label">
                            <div class="type-icon"></div>
                            <strong>Sugestão</strong>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" id="type-reclamacao" name="tipo" value="reclamacao">
                        <label for="type-reclamacao" class="type-label">
                            <div class="type-icon"></div>
                            <strong>Reclamação</strong>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" id="type-elogio" name="tipo" value="elogio">
                        <label for="type-elogio" class="type-label">
                            <div class="type-icon"></div>
                            <strong>Elogio</strong>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" id="type-bug" name="tipo" value="bug">
                        <label for="type-bug" class="type-label">
                            <div class="type-icon"></div>
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
    </div>
    
    <script src="assets/js/main.js?v=3"></script>
    <script src="assets/js/notifications.js?v=1"></script>
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
                const response = await fetch('php/submit_feedback.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('feedback-form-container').style.display = 'none';
                    document.getElementById('success-msg').classList.add('show');
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
</html>







