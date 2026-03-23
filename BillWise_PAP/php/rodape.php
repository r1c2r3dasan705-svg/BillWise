<?php
require_once __DIR__ . '/base.php';

$isRestrictedFooter = isset($panel_open_login) && $panel_open_login;
?>
<footer class="site-footer">
    <div class="footer-content-top">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h4>BillWise</h4>
                    <p>Ferramentas de literacia financeira para organizar despesas, criar objetivos e tomar decisões com mais clareza.</p>
                </div>

                <div class="footer-column">
                    <h4>Produto</h4>
                    <?php if ($isRestrictedFooter): ?>
                        <a href="#" class="open-restricted">Painel</a>
                        <a href="#" class="open-restricted">Despesas</a>
                        <a href="#" class="open-restricted">Orçamentos</a>
                        <a href="#" class="open-restricted">Simulador PPR</a>
                    <?php else: ?>
                        <a href="painel.php">Painel</a>
                        <a href="despesas.php">Despesas</a>
                        <a href="orcamento.php">Orçamentos</a>
                        <a href="calculadora_ppr.php">Simulador PPR</a>
                    <?php endif; ?>
                </div>

                <div class="footer-column">
                    <h4>Suporte</h4>
                    <a href="perguntas_frequentes.php">FAQ</a>
                    <a href="sobre.php">Sobre Nós</a>
                    <a href="feedback.php">Feedback</a>
                </div>
            </div>

            <p style="margin-top: 1.5rem;">&copy; 2026 BillWise. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

