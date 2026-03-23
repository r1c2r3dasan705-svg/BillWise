<?php
require_once __DIR__ . '/base.php';

billwise_require_auth();

$user_name = billwise_user_name();
$user_initials = strtoupper(substr(trim($_SESSION['name'] ?? 'U'), 0, 1));
$session_email = strtolower(trim($_SESSION['email'] ?? ''));
$is_admin = !empty($_SESSION['is_admin']) || $session_email === 'supbillwise@gmail.com';
$current_page = basename($_SERVER['PHP_SELF']);
$nav_base = isset($sidebar_base) ? $sidebar_base : '';
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?php echo $nav_base; ?>painel.php" class="sidebar-logo">BillWise</a>
        <div class="sidebar-user">
            <div class="user-avatar"><?php echo billwise_escape($user_initials); ?></div>
            <div class="user-info">
                <span class="user-name"><?php echo $user_name; ?></span>
                <span class="user-role"><?php echo $is_admin ? 'Administrador' : 'Utilizador'; ?></span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <h3 class="nav-section-title">Principal</h3>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>painel.php" class="nav-link <?php echo $current_page === 'painel.php' ? 'active' : ''; ?>">
                        <span class="nav-text">Painel</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <h3 class="nav-section-title">Finanças</h3>
            <ul class="nav-menu">
                <li class="nav-item"><a href="<?php echo $nav_base; ?>despesas.php" class="nav-link <?php echo $current_page === 'despesas.php' ? 'active' : ''; ?>"><span class="nav-text">Despesas</span></a></li>
                <li class="nav-item"><a href="<?php echo $nav_base; ?>orcamento.php" class="nav-link <?php echo $current_page === 'orcamento.php' ? 'active' : ''; ?>"><span class="nav-text">Orçamentos</span></a></li>
                <li class="nav-item"><a href="<?php echo $nav_base; ?>calculadora_ppr.php" class="nav-link <?php echo $current_page === 'calculadora_ppr.php' ? 'active' : ''; ?>"><span class="nav-text">Calculadora PPR</span></a></li>
                <li class="nav-item"><a href="<?php echo $nav_base; ?>simulador_investimentos.php" class="nav-link <?php echo $current_page === 'simulador_investimentos.php' ? 'active' : ''; ?>"><span class="nav-text">Investimentos</span></a></li>
            </ul>
        </div>

        <div class="nav-section">
            <h3 class="nav-section-title">Suporte</h3>
            <ul class="nav-menu">
                <li class="nav-item"><a href="<?php echo $nav_base; ?>ajuda.php" class="nav-link <?php echo $current_page === 'ajuda.php' ? 'active' : ''; ?>"><span class="nav-text">Ajuda</span></a></li>
                <li class="nav-item"><a href="<?php echo $nav_base; ?>feedback.php" class="nav-link <?php echo $current_page === 'feedback.php' ? 'active' : ''; ?>"><span class="nav-text">Feedback</span></a></li>
            </ul>
        </div>

        <div class="nav-section">
            <h3 class="nav-section-title">Comunidade</h3>
            <ul class="nav-menu">
                <li class="nav-item"><a href="<?php echo $nav_base; ?>forum.php" class="nav-link <?php echo $current_page === 'forum.php' ? 'active' : ''; ?>"><span class="nav-text">Fórum</span></a></li>
            </ul>
        </div>

        <div class="nav-section">
            <h3 class="nav-section-title">Conta</h3>
            <ul class="nav-menu">
                <li class="nav-item"><a href="<?php echo $nav_base; ?>definicoes.php" class="nav-link <?php echo $current_page === 'definicoes.php' ? 'active' : ''; ?>"><span class="nav-text">Definições</span></a></li>
                <?php if ($is_admin): ?>
                    <li class="nav-item"><a href="<?php echo $nav_base; ?>admin/painel_admin.php" class="nav-link <?php echo $current_page === 'painel_admin.php' ? 'active' : ''; ?>"><span class="nav-text">Admin</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="sidebar-footer">
        <button class="logout-btn" type="button" onclick="window.location.href='<?php echo $nav_base; ?>php/sair.php'">
            <span>Terminar Sessão</span>
        </button>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<button class="sidebar-toggle" id="sidebarToggle" type="button">Menu</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (!sidebar || !sidebarToggle || !sidebarOverlay) {
        return;
    }

    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
    });

    sidebarOverlay.addEventListener('click', function () {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    });
});
</script>

