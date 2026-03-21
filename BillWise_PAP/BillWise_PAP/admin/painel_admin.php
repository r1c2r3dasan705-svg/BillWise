<?php

session_start();
require_once __DIR__ . '/acesso_admin.php';
requireAdminPageAccess();

// Nome apresentado no topo do painel (com fallback seguro).
$user_name = htmlentities($_SESSION['name'] ?? 'Administrador');
// Token CSRF reutilizado nos formulários da página.
$csrf_token = adminCsrfToken();

// Estrutura base para cartões de métricas.
$stats = [
    'users' => 0,
    'expenses' => 0,
    'budgets' => 0
];
$users = [];
$flash = $_GET['msg'] ?? '';

try {
    $pdo = getPDO();

    // Carrega contadores principais do sistema.
    $stats['users'] = (int)$pdo->query('SELECT COUNT(*) FROM utilizadores')->fetchColumn();
    $stats['expenses'] = (int)$pdo->query('SELECT COUNT(*) FROM despesas')->fetchColumn();
    $stats['budgets'] = (int)$pdo->query('SELECT COUNT(*) FROM orcamentos')->fetchColumn();

    // Lista utilizadores por ordem decrescente de criação.
    $stmt = $pdo->query('
        SELECT id, nome, email, criado_em
        FROM utilizadores
        ORDER BY id DESC
    ');
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    $flash = 'Erro ao carregar dados do backend.';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - BillWise</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=3">
    <link rel="stylesheet" href="../assets/css/sidebar.css?v=1">
    <link rel="stylesheet" href="../assets/css/buttons.css?v=3">
    <link rel="stylesheet" href="../assets/css/forms.css?v=3">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="dashboard-layout">
        <?php

$sidebar_base = '../'; include '../php/sidebar.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container admin-wrap">
                    <header class="admin-head">
                        <div>
                            <h1 class="admin-title">Painel de Administração</h1>
                            <p class="admin-sub">Area reservada para <?php echo $user_name; ?>. Aqui gere utilizadores.</p>
                        </div>
                    </header>

                    <?php

if (!empty($flash)): ?>
                        <div class="admin-flash"><?php echo htmlentities($flash); ?></div>
                    <?php endif; ?>

                    <section class="admin-grid">
                        <article class="admin-card">
                            <p class="admin-label">Utilizadores</p>
                            <p class="admin-value"><?php echo $stats['users']; ?></p>
                        </article>
                        <article class="admin-card">
                            <p class="admin-label">Despesas</p>
                            <p class="admin-value"><?php echo $stats['expenses']; ?></p>
                        </article>
                        <article class="admin-card">
                            <p class="admin-label">Orcamentos</p>
                            <p class="admin-value"><?php echo $stats['budgets']; ?></p>
                        </article>
                    </section>

                    <section class="panel">
                        <div class="panel-head">
                            <h2 class="panel-title">Utilizadores</h2>
                            <p class="panel-note">Pode apagar qualquer utilizador, exceto o admin principal e a sua conta atual.</p>
                        </div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Criado em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

foreach ($users as $item): ?>
                                        <?php

// Sinaliza contas que não podem ser apagadas.
$item_email = strtolower(trim((string)$item['email']));
$is_main_admin = $item_email === strtolower(ADMIN_EMAIL);
$is_current_admin = ((int)$item['id'] === (int)($_SESSION['user_id'] ?? 0));
?>
                                        <tr>
                                            <td><?php echo (int)$item['id']; ?></td>
                                            <td><?php echo htmlentities($item['nome']); ?></td>
                                            <td><?php echo htmlentities($item['email']); ?></td>
                                            <td><?php echo htmlentities($item['criado_em']); ?></td>
                                            <td>
                                                <?php

if ($is_main_admin || $is_current_admin): ?>
                                                    <span class="status-pill status-analise">Protegido</span>
                                                <?php

else: ?>
                                                    <form method="post" action="apagar_utilizador_admin.php" onsubmit="return confirm('Apagar este utilizador? Esta acao remove tambem despesas e orcamentos associados.');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>">
                                                        <input type="hidden" name="user_id" value="<?php echo (int)$item['id']; ?>">
                                                        <button type="submit" class="btn-danger-admin">Apagar</button>
                                                    </form>
                                                <?php

endif; ?>
                                            </td>
                                        </tr>
                                    <?php

endforeach; ?>
                                    <?php

if (empty($users)): ?>
                                        <tr><td colspan="5">Sem utilizadores.</td></tr>
                                    <?php

endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

