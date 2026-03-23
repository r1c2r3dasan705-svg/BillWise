<?php
// Iniciar sessão antes de qualquer output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o utilizador está logado
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? htmlentities($_SESSION['name']) : '';

if ($isLoggedIn) {
    header('Location: ajuda.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - BillWise</title>
    <?php if ($isLoggedIn): ?>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <?php else: ?>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/cabecalho.css?v=2">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/botoes.css?v=3">
    <link rel="stylesheet" href="assets/css/formularios.css?v=3">
    <link rel="stylesheet" href="assets/css/modais.css">
    <?php if (!$isLoggedIn): ?>
    <link rel="stylesheet" href="assets/css/rodape.css?v=2">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/pagina_inicial.css">
    <?php if ($isLoggedIn): ?>
    <link rel="stylesheet" href="assets/css/painel.css">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php if ($isLoggedIn): ?>
    <div class="dashboard-layout">
        <?php include 'php/barra_lateral.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container">
    <?php else: ?>
    <?php include 'php/cabecalho.php'; ?>
    <main class="main">
        <div class="container">
    <?php endif; ?>
    
    <?php if ($isLoggedIn): ?>
    <!-- FAQ para utilizadores logados - inclui formulário de feedback para dúvidas específicas -->
                    <section class="dashboard-hero">
                        <div class="hero-greeting">
                            <h1>Perguntas Frequentes</h1>
                            <p>Encontre respostas para as dúvidas mais comuns sobre o BillWise</p>
                        </div>
                    </section>
                    
                    <section class="page-section active">
                        <div class="faq-list" style="max-width: 800px; margin: 0 auto;">
                            <div class="faq-item">
                                <button class="faq-question">Como posso adicionar uma despesa?</button>
                                <div class="faq-answer">Para adicionar uma despesa, aceda à secção "Despesas" no menu lateral, clique em "Adicionar Despesa" e preencha os campos obrigatórios: valor, categoria e data.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">Como definir um orçamento?</button>
                                <div class="faq-answer">Na secção "Orçamento", clique em "Criar novo orçamento", selecione a categoria e defina o limite mensal desejado.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">A calculadora PPR é precisa?</button>
                                <div class="faq-answer">A calculadora fornece estimativas baseadas nos dados introduzidos. Os resultados são aproximados e não constituem aconselhamento financeiro profissional.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">Como posso alterar a minha palavra-passe?</button>
                                <div class="faq-answer">Aceda às "Definições" no menu lateral e utilize a opção "Alterar Palavra-passe" para atualizar as suas credenciais.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">Os meus dados estão seguros?</button>
                                <div class="faq-answer">Sim, utilizamos encriptação de nível bancário e cumprimos todas as normas de proteção de dados. Os seus dados financeiros estão seguros connosco.</div>
                            </div>
                        </div>
                        
                        <!-- Feedback form para utilizadores logados - permite enviar dúvidas específicas ou reportar problemas -->
                        <div class="feedback-section" style="max-width: 800px; margin: 3rem auto 0; background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h2 style="margin-bottom: 1.5rem;">Ainda precisa de ajuda?</h2>
                            <p style="margin-bottom: 1.5rem; color: var(--gray-600);">Envie-nos uma mensagem e responderemos brevemente.</p>
                            
                            <form id="faq-feedback-form">
                                <input type="hidden" name="tipo" value="duvida">
                                <div class="form-group">
                                    <label for="faq-nome">Nome</label>
                                    <input type="text" id="faq-nome" name="nome" value="<?php echo $userName; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="faq-email">Email</label>
                                    <input type="email" id="faq-email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="faq-assunto">Assunto</label>
                                    <input type="text" id="faq-assunto" name="assunto" placeholder="Resuma o tema da sua mensagem" required>
                                </div>
                                <div class="form-group">
                                    <label for="faq-mensagem">Mensagem</label>
                                    <textarea id="faq-mensagem" name="mensagem" rows="5" placeholder="Descreva em detalhe o seu problema ou dúvida..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                            </form>
                        </div>
                    </section>
    <?php else: ?>
    <!-- FAQ para utilizadores não logados - inclui formulário de contacto para suporte geral -->
                    <section class="page-section active">
                        <div class="faq-header-section">
                            <h1>Perguntas Frequentes</h1>
                            <p>Encontre respostas para as dúvidas mais comuns sobre o BillWise</p>
                        </div>
                        <div class="faq-list">
                            <div class="faq-item">
                                <button class="faq-question">Como posso adicionar uma despesa?</button>
                                <div class="faq-answer">Para adicionar uma despesa, aceda à secção "Despesas" no menu lateral, clique em "Adicionar Despesa" e preencha os campos obrigatórios: valor, categoria e data.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">Como definir um orçamento?</button>
                                <div class="faq-answer">Na secção "Orçamento", clique em "Criar novo orçamento", selecione a categoria e defina o limite mensal desejado.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">A calculadora PPR é precisa?</button>
                                <div class="faq-answer">A calculadora fornece estimativas baseadas nos dados introduzidos. Os resultados são aproximados e não constituem aconselhamento financeiro profissional.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">Como posso alterar a minha palavra-passe?</button>
                                <div class="faq-answer">Aceda às "Definições" no menu lateral e utilize a opção "Alterar Palavra-passe" para atualizar as suas credenciais.</div>
                            </div>
                            <div class="faq-item">
                                <button class="faq-question">Os meus dados estão seguros?</button>
                                <div class="faq-answer">Sim, utilizamos encriptação de nível bancário e cumprimos todas as normas de proteção de dados. Os seus dados financeiros estão seguros connosco.</div>
                            </div>
                        </div>
                        
                        <!-- Formulário de contacto para utilizadores não logados - permite enviar mensagens para suporte geral -->
                        <div class="faq-contact-form" style="max-width: 600px; margin: 3rem auto 0; background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h2 style="margin-bottom: 0.5rem;">Ainda precisa de ajuda?</h2>
                            <p style="margin-bottom: 1.5rem; color: var(--gray-600);">Envie-nos uma mensagem e responderemos ao seu email.</p>
                            
                            <form id="faq-contact-form">
                                <div class="form-group">
                                    <label for="contact-nome">Nome</label>
                                    <input type="text" id="contact-nome" name="nome" placeholder="O seu nome" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact-email">Email</label>
                                    <input type="email" id="contact-email" name="email" placeholder="O seu email" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact-assunto">Assunto</label>
                                    <input type="text" id="contact-assunto" name="assunto" placeholder="Resuma o tema da sua mensagem" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact-mensagem">Mensagem</label>
                                    <textarea id="contact-mensagem" name="mensagem" rows="5" placeholder="Descreva o seu problema ou dúvida..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width: 100%;">Enviar Mensagem</button>
                            </form>
                        </div>
                    </section>
    <?php endif; ?>
    
                </div>
            </main>
    <?php if ($isLoggedIn): ?>
        </div>
    </div>
    <?php else: ?>
    <?php include 'php/rodape.php'; ?>
    <?php endif; ?>
    
    <script src="assets/js/notificacoes.js?v=1"></script>
    <script src="assets/js/principal.js"></script>
    <script>
        // Script para interatividade da FAQ e envio de formulários
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const faqItem = question.parentElement;
                    const answer = question.nextElementSibling;
                    const isOpen = answer.style.display === 'block';
                    
                    // Fechar todas as respostas
                    document.querySelectorAll('.faq-item').forEach(item => {
                        item.classList.remove('active');
                        item.querySelector('.faq-answer').style.display = 'none';
                    });
                    
                    //  Abrir a resposta clicada se não estava aberta
                    if (!isOpen) {
                        faqItem.classList.add('active');
                        answer.style.display = 'block';
                    }
                });
            });
            
            // formulário de feedback para utilizadores logados
            const feedbackForm = document.getElementById('faq-feedback-form');
            if (feedbackForm) {
                feedbackForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const formData = new FormData(feedbackForm);
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
                            if (typeof Notifications !== 'undefined') {
                                Notifications.success('Mensagem enviada com sucesso! Iremos responder em breve.');
                            } else {
                                alert('Mensagem enviada com sucesso! Iremos responder em breve.');
                            }
                            feedbackForm.reset();
                        } else {
                            if (typeof Notifications !== 'undefined') {
                                Notifications.error(result.error || 'Erro ao enviar mensagem');
                            } else {
                                alert(result.error || 'Erro ao enviar mensagem');
                            }
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        if (typeof Notifications !== 'undefined') {
                            Notifications.error('Erro ao enviar mensagem. Tente novamente.');
                        } else {
                            alert('Erro ao enviar mensagem. Tente novamente.');
                        }
                    }
                });
            }
            
            // formulário de contacto para utilizadores não logados
            const contactForm = document.getElementById('faq-contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const formData = new FormData(contactForm);
                    const data = {
                        nome: formData.get('nome'),
                        email: formData.get('email'),
                        assunto: formData.get('assunto'),
                        mensagem: formData.get('mensagem')
                    };
                    
                    try {
                        const response = await fetch('php/enviar_email_suporte.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data)
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            if (typeof Notifications !== 'undefined') {
                                Notifications.success('Mensagem enviada com sucesso! Iremos responder ao seu email.');
                            } else {
                                alert('Mensagem enviada com sucesso! Iremos responder ao seu email.');
                            }
                            contactForm.reset();
                        } else {
                            if (typeof Notifications !== 'undefined') {
                                Notifications.error(result.error || 'Erro ao enviar mensagem');
                            } else {
                                alert(result.error || 'Erro ao enviar mensagem');
                            }
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        if (typeof Notifications !== 'undefined') {
                            Notifications.error('Erro ao enviar mensagem. Tente novamente.');
                        } else {
                            alert('Erro ao enviar mensagem. Tente novamente.');
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>



