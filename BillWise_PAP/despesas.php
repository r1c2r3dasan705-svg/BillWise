<?php
// Página de gestão de despesas
// Permite adicionar, editar e eliminar despesas
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
    <title>Despesas - BillWise</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                            <h1 style="font-size: 1.75rem; margin-bottom: 0.25rem;">Gestor de Despesas</h1>
                            <p style="font-size: 0.95rem;">Acompanhe os seus gastos diários</p>
                        </div>
                    </section>

            <section class="page-section active">

                <div class="expense-list">
                    <h2>Despesas</h2>
                    <div id="expense-items" class="expense-items"></div>
                </div>

                <button class="btn btn-primary" id="open-add-expense">Adicionar Despesa</button>
            </section>
        </div>
    </main>

    <div id="expense-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Despesa</h2>
                <button class="close-btn">&times;</button>
            </div>
            <form id="expense-form">
                <div class="form-group">
                    <label for="amount">Valor (€)</label>
                    <input type="number" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="category">Categoria</label>
                    <select id="category" name="category" required>
                        <option value="Alimentação">Alimentação</option>
                        <option value="Transporte">Transporte</option>
                        <option value="Saúde">Saúde</option>
                        <option value="Entretenimento">Entretenimento</option>
                        <option value="Educação">Educação</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Data</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <input type="text" id="description" name="description">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary close-btn">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
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

