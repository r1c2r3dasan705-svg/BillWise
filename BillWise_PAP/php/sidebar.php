<?php
// Componente de sidebar - navegação lateral para paginas autenticadas
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Preparar dados do utilizador para exibição
$user_name = htmlentities($_SESSION['name']);
$user_initials = strtoupper(substr($user_name, 0, 1));
$session_email = strtolower(trim($_SESSION['email'] ?? ''));
$is_admin = !empty($_SESSION['is_admin']) || $session_email === 'supbillwise@gmail.com';

// Detetar pagina atual para destacar link ativo
$current_page = basename($_SERVER['PHP_SELF']);
$nav_base = isset($sidebar_base) ? $sidebar_base : '';
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?php echo $nav_base; ?>dashboard.php" class="sidebar-logo">BillWise</a>
        <div class="sidebar-user">
            <div class="user-avatar"><?php echo $user_initials; ?></div>
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
                    <a href="<?php echo $nav_base; ?>dashboard.php" class="nav-link <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="14" y="14" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                            </svg>
                        </span>
                        <span class="nav-text">Painel</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <h3 class="nav-section-title">Financas</h3>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>despesas.php" class="nav-link <?php echo $current_page === 'despesas.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                <line x1="1" y1="10" x2="23" y2="10"/>
                            </svg>
                        </span>
                        <span class="nav-text">Despesas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>orcamento.php" class="nav-link <?php echo $current_page === 'orcamento.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="6"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        </span>
                        <span class="nav-text">Orcamentos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>calculadora_ppr.php" class="nav-link <?php echo $current_page === 'calculadora_ppr.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="4" y="2" width="16" height="20" rx="2" ry="2"/>
                                <line x1="8" y1="6" x2="16" y2="6"/>
                                <line x1="8" y1="10" x2="10" y2="10"/>
                                <line x1="14" y1="10" x2="16" y2="10"/>
                                <line x1="8" y1="14" x2="10" y2="14"/>
                                <line x1="14" y1="14" x2="16" y2="14"/>
                                <line x1="8" y1="18" x2="16" y2="18"/>
                            </svg>
                        </span>
                        <span class="nav-text">Calculadora PPR</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <h3 class="nav-section-title">Suporte</h3>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>feedback.php" class="nav-link <?php echo $current_page === 'feedback.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </span>
                        <span class="nav-text">Feedback</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>ajuda.php" class="nav-link <?php echo $current_page === 'ajuda.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.5 9a2.5 2.5 0 0 1 5 0c0 2-2.5 2-2.5 4"/>
                                <circle cx="12" cy="17.5" r="0.75" fill="currentColor" stroke="none"/>
                            </svg>
                        </span>
                        <span class="nav-text">Ajuda</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <h3 class="nav-section-title">Conta</h3>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>definicoes.php" class="nav-link <?php echo $current_page === 'definicoes.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M12 1v6m0 6v6m5.66-15.66l-4.24 4.24m0 6l-4.24 4.24M23 12h-6m-6 0H1m15.66 5.66l-4.24-4.24m0-6l-4.24-4.24"/>
                            </svg>
                        </span>
                        <span class="nav-text">Definicoes</span>
                    </a>
                </li>
                <?php if ($is_admin): ?>
                <li class="nav-item">
                    <a href="<?php echo $nav_base; ?>admin/backend.php" class="nav-link <?php echo $current_page === 'backend.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <path d="M3 9h18"/>
                                <path d="M9 21V9"/>
                            </svg>
                        </span>
                        <span class="nav-text">Backend</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="sidebar-footer">
        <button class="logout-btn" onclick="window.location.href='<?php echo $nav_base; ?>php/logout.php'">
            <span class="nav-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </span>
            <span>Terminar Sessao</span>
        </button>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<button class="sidebar-toggle" id="sidebarToggle">
    Menu
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }
});
</script>


