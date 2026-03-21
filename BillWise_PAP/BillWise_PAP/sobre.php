<?php
// Iniciar sessão antes de qualquer output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - BillWise</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/header.css?v=2">
    <link rel="stylesheet" href="assets/css/buttons.css?v=3">
    <link rel="stylesheet" href="assets/css/forms.css?v=3">
    <link rel="stylesheet" href="assets/css/modals.css">
    <link rel="stylesheet" href="assets/css/footer.css?v=2">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'php/header.php'; ?>
    <main class="main">
        <div class="container">
            <section class="page-section active">
                <div class="about-hero">
                    <h1>Sobre o BillWise</h1>
                    <p>Conheça mais sobre a nossa missão e valores</p>
                </div>
                
                <div class="about-grid">
                    <div class="about-card">
                                                <h2>A Nossa Missão</h2>
                        <p>Simplificar a literacia financeira para todos, fornecendo ferramentas intuitivas e acessíveis para gerir despesas e orçamentos de forma eficiente.</p>
                    </div>
                    
                    <div class="about-card">
                                                <h2>Porquê BillWise?</h2>
                        <p>O BillWise foi criado para ajudar pessoas comuns a tomarem controlo das suas finanças pessoais. Acreditamos que uma boa gestão financeira deve ser simples e acessível a todos.</p>
                    </div>
                    
                    <div class="about-card">
                                                <h2>Segurança e Privacidade</h2>
                        <p>Os seus dados estão sempre protegidos com encriptação de nível bancário. Cumprimos todas as normas de proteção de dados e nunca partilhamos informações pessoais.</p>
                    </div>
                    
                    <div class="about-card">
                                                <h2>Transparência</h2>
                        <p>Acreditamos em total transparência na forma como ajudá-lo a gerir o seu dinheiro. Sem custos ocultos, sem surpresas.</p>
                    </div>
                </div>
                
                <div class="about-contact">
                    <h2>Contacto</h2>
                    <p>Para dúvidas ou sugestões, utilize o formulário de feedback na sua conta ou contacte-nos através do email:</p>
                    <a href="faq.php" class="about-contact-email">
                       Perguntas Frequentes
                    </a>
                </div>
            </section>
        </div>
    </main>
    <script src="assets/js/notifications.js?v=1"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
