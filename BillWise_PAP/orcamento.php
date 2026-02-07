<?php
// Página de gestão de orçamentos
// Permite criar e gerir limites de gastos por categoria
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
    <title>Orçamento - BillWise</title>
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
                            <h1 style="font-size: 1.75rem; margin-bottom: 0.25rem;">Orçamento Mensal</h1>
                            <p style="font-size: 0.95rem;">Defina o seu orçamento e acompanhe por categoria</p>
                        </div>
                    </section>

            <section class="page-section active">

                <div class="budget-categories">
                    <h2>Criar novo orçamento</h2>
                    <form id="budget-form">
                        <div class="form-group">
                            <label for="name">Categoria</label>
                            <select id="name" name="name" required>
                                <option value="" disabled selected>Selecione uma categoria</option>
                                <option value="Alimentação">Alimentação</option>
                                <option value="Transporte">Transporte</option>
                                <option value="Saúde">Saúde</option>
                                <option value="Entretenimento">Entretenimento</option>
                                <option value="Educação">Educação</option>
                                <option value="Outros">Outros</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="limit">Limite (€)</label>
                            <input type="number" id="limit" name="limit" required>
                        </div>
                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit">Guardar Orçamento</button>
                        </div>
                    </form>
                </div>

                <div class="category-list" id="budget-list">
                </div>
            </section>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/notifications.js?v=1"></script>
    <script src="assets/js/main.js"></script>
    <?php include 'php/footer.php'; ?>
</body>
</html>

