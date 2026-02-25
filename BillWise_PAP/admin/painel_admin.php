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
    <style>
        :root {
            --admin-bg: #f3f6fb;
            --card-bg: #ffffff;
            --line: #dce3ef;
            --text: #0f172a;
            --muted: #64748b;
            --danger: #dc2626;
            --danger-soft: #fee2e2;
            --ok: #166534;
            --ok-soft: #dcfce7;
        }
        .admin-wrap { padding: 1.2rem; background: var(--admin-bg); min-height: 100vh; }
        .admin-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem; }
        .admin-title { margin: 0; color: var(--text); font-size: 1.8rem; }
        .admin-sub { margin: 0.2rem 0 0; color: var(--muted); }
        .admin-grid {
            display: grid;
            gap: 0.8rem;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            margin-bottom: 1rem;
        }
        .admin-card {
            background: var(--card-bg);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0.9rem;
        }
        .admin-card p { margin: 0; }
        .admin-label { color: var(--muted); font-size: 0.85rem; margin-bottom: 0.4rem; }
        .admin-value { color: var(--text); font-size: 1.65rem; font-weight: 700; }
        .admin-flash {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            border-radius: 10px;
            padding: 0.7rem 0.9rem;
            margin-bottom: 1rem;
            color: var(--text);
        }
        .panel {
            background: var(--card-bg);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.8rem;
        }
        .panel-title { margin: 0; color: var(--text); font-size: 1.1rem; }
        .panel-note { margin: 0; color: var(--muted); font-size: 0.9rem; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; }
        th, td {
            border-bottom: 1px solid var(--line);
            text-align: left;
            padding: 0.62rem 0.4rem;
            color: var(--text);
            vertical-align: top;
        }
        th { color: #334155; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.02em; }
        tr:last-child td { border-bottom: 0; }
        .status-pill {
            padding: 0.2rem 0.45rem;
            border-radius: 999px;
            font-size: 0.78rem;
            border: 1px solid transparent;
            display: inline-block;
        }
        .status-pendente { background: #fff7ed; color: #9a3412; border-color: #fed7aa; }
        .status-analise { background: #dbeafe; color: #1e3a8a; border-color: #bfdbfe; }
        .status-resolvido { background: var(--ok-soft); color: var(--ok); border-color: #86efac; }
        .inline-actions { display: flex; gap: 0.45rem; align-items: center; flex-wrap: wrap; }
        .input-sm { min-width: 130px; padding: 0.35rem 0.45rem; border: 1px solid #cbd5e1; border-radius: 8px; }
        .btn-danger {
            border: 1px solid #fecaca;
            background: var(--danger-soft);
            color: var(--danger);
            padding: 0.45rem 0.65rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .bulk-bar {
            position: sticky;
            bottom: 0;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 10px;
            margin-top: 0.7rem;
            padding: 0.65rem;
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .bulk-label { color: var(--muted); font-size: 0.88rem; margin-right: 0.2rem; }
        .check-col { width: 36px; }
        @media (max-width: 768px) {
            .admin-title { font-size: 1.4rem; }
            .panel-head { align-items: flex-start; flex-direction: column; }
        }
    </style>
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
                            <p class="admin-sub">Area reservada para <?php

echo $user_name; ?>. Aqui gere utilizadores.</p>
                        </div>
                    </header>

                    <?php

if (!empty($flash)): ?>
                        <div class="admin-flash"><?php

echo htmlentities($flash); ?></div>
                    <?php

endif; ?>

                    <section class="admin-grid">
                        <article class="admin-card">
                            <p class="admin-label">Utilizadores</p>
                            <p class="admin-value"><?php

echo $stats['users']; ?></p>
                        </article>
                        <article class="admin-card">
                            <p class="admin-label">Despesas</p>
                            <p class="admin-value"><?php

echo $stats['expenses']; ?></p>
                        </article>
                        <article class="admin-card">
                            <p class="admin-label">Orcamentos</p>
                            <p class="admin-value"><?php

echo $stats['budgets']; ?></p>
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
                                            <td><?php

echo (int)$item['id']; ?></td>
                                            <td><?php

echo htmlentities($item['nome']); ?></td>
                                            <td><?php

echo htmlentities($item['email']); ?></td>
                                            <td><?php

echo htmlentities($item['criado_em']); ?></td>
                                            <td>
                                                <?php

if ($is_main_admin || $is_current_admin): ?>
                                                    <span class="status-pill status-analise">Protegido</span>
                                                <?php

else: ?>
                                                    <form method="post" action="apagar_utilizador_admin.php" onsubmit="return confirm('Apagar este utilizador? Esta acao remove tambem despesas e orcamentos associados.');">
                                                        <input type="hidden" name="csrf_token" value="<?php

echo htmlentities($csrf_token); ?>">
                                                        <input type="hidden" name="user_id" value="<?php

echo (int)$item['id']; ?>">
                                                        <button type="submit" class="btn-danger">Apagar</button>
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









