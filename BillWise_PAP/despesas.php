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
    <title>Despesas - BillWise</title>
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
                            <h1 style="font-size: 1.75rem; margin-bottom: 0.25rem;">Gestor de Despesas</h1>
                            <p style="font-size: 0.95rem;">Adicione, filtre, edite e remova despesas num único fluxo.</p>
                        </div>
                    </section>

                    <section class="page-section active">
                        <div class="expense-list">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                                <h2>Despesas</h2>
                                <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                                    <select id="category-filter" style="padding: 0.5rem 1rem; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; cursor: pointer;">
                                        <option value="">Todas as categorias</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo billwise_escape($category); ?>"><?php echo billwise_escape($category); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-primary" id="open-add-expense" type="button">Adicionar Despesa</button>
                                </div>
                            </div>

                            <div id="expense-items" class="expense-items"></div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div id="expense-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Adicionar Despesa</h2>
                <button class="close-btn" type="button">&times;</button>
            </div>
            <form id="expense-form">
                <div class="form-group">
                    <label for="amount">Valor (€)</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="category">Categoria</label>
                    <select id="category" name="category" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo billwise_escape($category); ?>"><?php echo billwise_escape($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Data</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <input type="text" id="description" name="description" maxlength="255">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary close-btn">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/notificacoes.js?v=1"></script>
    <script src="assets/js/principal.js?v=3"></script>
</body>
</html>


