<?php
// Calculadora de Plano Poupança Reforma (PPR)
// Simula o valor acumulado até à reforma com juros compostos
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora PPR - BillWise</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/sidebar.css?v=1">
    <link rel="stylesheet" href="assets/css/buttons.css">
    <link rel="stylesheet" href="assets/css/forms.css">
    <link rel="stylesheet" href="assets/css/modals.css">
    <link rel="stylesheet" href="assets/css/footer.css?v=2">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/sidebar.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container">
                    <!-- Hero Section -->
                    <section class="dashboard-hero" style="padding: 1.5rem 0; margin-bottom: 1.5rem;">
                        <div class="hero-greeting">
                            <h1 style="font-size: 1.75rem; margin-bottom: 0.25rem;">Calculadora PPR</h1>
                            <p style="font-size: 0.95rem;">Simule quanto poupará até a reforma com o Plano Poupança Reforma</p>
                        </div>
                    </section>

                    <!-- Calculator Card -->
                    <div style="max-width: 600px; margin: 0 auto;">
                        <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <div class="form-group">
                                <label for="initial" style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; display: block;">
                                    <svg style="display: inline-block; width: 20px; height: 20px; margin-right: 0.5rem; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Capital Inicial (€)
                                </label>
                                <input type="number" id="initial" value="1000" step="100" min="0" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                            </div>
                            <div class="form-group" style="margin-top: 1.5rem;">
                                <label for="contrib" style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; display: block;">
                                    <svg style="display: inline-block; width: 20px; height: 20px; margin-right: 0.5rem; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    Contribuição Mensal (€)
                                </label>
                                <input type="number" id="contrib" value="100" step="10" min="0" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                            </div>
                            <div class="form-group" style="margin-top: 1.5rem;">
                                <label for="years" style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; display: block;">
                                    <svg style="display: inline-block; width: 20px; height: 20px; margin-right: 0.5rem; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Anos até Reforma
                                </label>
                                <input type="number" id="years" value="20" step="1" min="1" max="50" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                            </div>
                            <div class="form-group" style="margin-top: 1.5rem;">
                                <label for="rate" style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem; display: block;">
                                    <svg style="display: inline-block; width: 20px; height: 20px; margin-right: 0.5rem; vertical-align: middle;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                    Taxa de Retorno Anual (%)
                                </label>
                                <input type="number" id="rate" value="4" step="0.1" min="0" max="20" style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                                <small style="color: #64748b; display: block; margin-top: 0.25rem;">Média histórica: 3-6% ao ano</small>
                            </div>
                            <div style="margin-top: 2rem;">
                                <button class="btn btn-primary" id="calcPpr" style="width: 100%; padding: 1rem; font-size: 1.1rem;">Calcular Poupança</button>
                            </div>
                        </div>

                        <!-- Result Display -->
                        <div id="ppr-result" style="display:none; margin-top: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);"></div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/notifications.js?v=1"></script>
    <script src="assets/js/main.js"></script>
    <?php include 'php/footer.php'; ?>
</body>
</html>