<?php
require_once 'php/base.php';

billwise_require_auth();
$categories = billwise_categories();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento - BillWise</title>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <link rel="stylesheet" href="assets/css/botoes.css?v=3">
    <link rel="stylesheet" href="assets/css/formularios.css?v=3">
    <link rel="stylesheet" href="assets/css/modais.css?v=3">
    <link rel="stylesheet" href="assets/css/painel.css?v=2">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/barra_lateral.php'; ?>
        <div class="main-content-wrapper">
            <main id="main-content" class="main">
                <div class="container">
                    <section class="dashboard-hero" style="padding: 1.5rem 0; margin-bottom: 1.5rem;">
                        <div class="hero-greeting">
                            <h1 style="font-size: 1.75rem; margin-bottom: 0.25rem;">Orçamento Mensal</h1>
                            <p style="font-size: 0.95rem;">Defina limites por categoria e acompanhe o respetivo consumo.</p>
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
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo billwise_escape($category); ?>"><?php echo billwise_escape($category); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="limit">Limite (€)</label>
                                    <input type="number" id="limit" name="limit" step="0.01" min="0" required>
                                </div>
                                <div class="form-actions">
                                    <button class="btn btn-primary" type="submit">Guardar Orçamento</button>
                                </div>
                            </form>
                        </div>

                        <div class="category-list" id="budget-list"></div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/notificacoes.js?v=1"></script>
    <script src="assets/js/principal.js?v=3"></script>
</body>
</html>


