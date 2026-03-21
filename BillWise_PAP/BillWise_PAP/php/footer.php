<?php
// Componente de footer reutilizável
?>
<footer class="site-footer">
    <div class="footer-content-top">
        <div class="container">
            <div class="footer-grid">
                <!-- Seção de marca e descrição -->
                <div class="footer-brand">
                    <p>Ferramentas de literacia financeira para você dominar suas finanças e alcançar seus objetivos.</p>
                </div>

                <!-- Links de navegação -->
                <div class="footer-column">
                    <h4>Produto</h4>
                    <?php if (isset($panel_open_login) && $panel_open_login): ?>
<a href="#" class="open-restricted">Painel</a>
                        <a href="#" class="open-restricted">Despesas</a>
                        <a href="#" class="open-restricted">Orçamentos</a>
                        <a href="#" class="open-restricted">Simulador PPR</a>
                        <a href="#" class="open-restricted">Simulador de Investimentos</a>
                    <?php else: ?>
<a href="painel.php">Painel</a>
                        <a href="despesas.php">Despesas</a>
                        <a href="orcamento.php">Orçamentos</a>
                        <a href="calculadora_ppr.php">Simulador PPR</a>
                    <?php endif; ?>
                </div>

                <!-- Suporte -->
                <div class="footer-column">
                    <h4>Suporte</h4>
                    <a href="faq.php">FAQ</a>
                    <a href="sobre.php?tipo=bug">Sobre Nós</a>
                </div>
                <p>&copy; 2026 BillWise. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>

 
                
                
                  
    
                
