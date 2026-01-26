<?php
// Componente de footer reutilizável
?>
<footer class="site-footer">
    <div class="footer-content-top">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand Section -->
                <div class="footer-brand">
                    <p>Ferramentas de literacia financeira para você dominar suas finanças e alcançar seus objetivos.</p>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h4>Produto</h4>
                    <?php if (isset($panel_open_login) && $panel_open_login): ?>
                        <a href="#" class="open-restricted">Dashboard</a>
                        <a href="#" class="open-restricted">Despesas</a>
                        <a href="#" class="open-restricted">Orçamentos</a>
                        <a href="#" class="open-restricted">Simulador PPR</a>
                    <?php else: ?>
                        <a href="dashboard.php">Dashboard</a>
                        <a href="despesas.php">Despesas</a>
                        <a href="orcamento.php">Orçamentos</a>
                        <a href="calculadora_ppr.php">Simulador PPR</a>
                    <?php endif; ?>
                </div>

                <!-- Suporte -->
                <div class="footer-column">
                    <h4>Suporte</h4>
                    <a href="feedback.php">Feedback & Reclamações</a>
                    <a href="#faq">FAQ</a>
                    <a href="feedback.php?tipo=bug">Reportar Bug</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <p>&copy; 2025 BillWise. Todos os direitos reservados.</p>
                <div class="footer-badges">
                    <span class="badge-footer"><svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M12 1L3 5v6c0 5 3.8 9.7 9 11 5.2-1.3 9-6 9-11V5l-9-4zM11 10h2v5h-2zM11 6h2v2h-2z"/></svg> Seguro</span>
                    <span class="badge-footer"><svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1 14.5l-4-4 1.4-1.4L11 13.7l5.6-5.6L18 9l-7 7.5z"/></svg> Certificado</span>
                    <span class="badge-footer"><svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M13 2v8h7l-8 12v-8H5z"/></svg> Rápido</span>
                </div>
            </div>
        </div>
    </div>
</footer>
