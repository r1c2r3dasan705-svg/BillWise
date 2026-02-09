<?php
session_start();
require_once '../php/admin/admin_access.php';
requireAdminPageAccess();

$user_name = htmlentities($_SESSION['name'] ?? 'Administrador');
$csrf_token = adminCsrfToken();

$stats = [
    'users' => 0,
    'expenses' => 0,
    'budgets' => 0,
    'feedback' => 0
];
$users = [];
$feedback = [];
$flash = $_GET['msg'] ?? '';

try {
    $pdo = getPDO();

    $stats['users'] = (int)$pdo->query('SELECT COUNT(*) FROM utilizadores')->fetchColumn();
    $stats['expenses'] = (int)$pdo->query('SELECT COUNT(*) FROM despesas')->fetchColumn();
    $stats['budgets'] = (int)$pdo->query('SELECT COUNT(*) FROM orcamentos')->fetchColumn();
    $stats['feedback'] = (int)$pdo->query('SELECT COUNT(*) FROM feedback')->fetchColumn();

    $stmt = $pdo->query('
        SELECT id, nome, email, criado_em
        FROM utilizadores
        ORDER BY id DESC
    ');
    $users = $stmt->fetchAll();

    $stmt = $pdo->query('
        SELECT id, nome, email, tipo, assunto, status, criado_em
        FROM feedback
        ORDER BY id DESC
    ');
    $feedback = $stmt->fetchAll();
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
        <?php $sidebar_base = '../'; include '../php/sidebar.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container admin-wrap">
                    <header class="admin-head">
                        <div>
                            <h1 class="admin-title">Painel de AdministraÃ§Ã£o</h1>
                            <p class="admin-sub">Area reservada para <?php echo $user_name; ?>. Aqui gere utilizadores e feedback.</p>
                        </div>
                    </header>

                    <?php if (!empty($flash)): ?>
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
                        <article class="admin-card">
                            <p class="admin-label">Feedback</p>
                            <p class="admin-value"><?php echo $stats['feedback']; ?></p>
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
                                        <th>Acoes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $item): ?>
                                        <?php
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
                                                <?php if ($is_main_admin || $is_current_admin): ?>
                                                    <span class="status-pill status-analise">Protegido</span>
                                                <?php else: ?>
                                                    <form method="post" action="../php/admin/admin_user_delete.php" onsubmit="return confirm('Apagar este utilizador? Esta acao remove tambem despesas e orcamentos associados.');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>">
                                                        <input type="hidden" name="user_id" value="<?php echo (int)$item['id']; ?>">
                                                        <button type="submit" class="btn-danger">Apagar</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($users)): ?>
                                        <tr><td colspan="5">Sem utilizadores.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section class="panel">
                        <div class="panel-head">
                            <h2 class="panel-title">Feedback</h2>
                            <p class="panel-note">Gestao individual e em massa (selecionados ou todos).</p>
                        </div>

                        <form method="post" action="../php/admin/admin_feedback_manage.php" id="feedbackBulkForm">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlentities($csrf_token); ?>">

                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="check-col"><input type="checkbox" id="selectAllFeedback" aria-label="Selecionar todos"></th>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Tipo</th>
                                            <th>Assunto</th>
                                            <th>Status</th>
                                            <th>Acoes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($feedback as $item): ?>
                                            <?php
                                                $status_class = 'status-pendente';
                                                if ($item['status'] === 'em_analise') $status_class = 'status-analise';
                                                if ($item['status'] === 'resolvido') $status_class = 'status-resolvido';
                                            ?>
                                            <tr>
                                                <td class="check-col">
                                                    <input type="checkbox" name="feedback_ids[]" value="<?php echo (int)$item['id']; ?>" class="feedback-check">
                                                </td>
                                                <td><?php echo (int)$item['id']; ?></td>
                                                <td><?php echo htmlentities($item['nome']); ?></td>
                                                <td><?php echo htmlentities($item['email']); ?></td>
                                                <td><?php echo htmlentities($item['tipo']); ?></td>
                                                <td><?php echo htmlentities($item['assunto']); ?></td>
                                                <td><span class="status-pill <?php echo $status_class; ?>"><?php echo htmlentities($item['status']); ?></span></td>
                                                <td>
                                                    <div class="inline-actions">
                                                        <select name="single_status_<?php echo (int)$item['id']; ?>" class="input-sm" id="singleStatus<?php echo (int)$item['id']; ?>">
                                                            <option value="pendente" <?php echo $item['status'] === 'pendente' ? 'selected' : ''; ?>>pendente</option>
                                                            <option value="em_analise" <?php echo $item['status'] === 'em_analise' ? 'selected' : ''; ?>>em_analise</option>
                                                            <option value="resolvido" <?php echo $item['status'] === 'resolvido' ? 'selected' : ''; ?>>resolvido</option>
                                                        </select>
                                                        <button
                                                            type="submit"
                                                            class="btn btn-primary"
                                                            name="action"
                                                            value="update_one"
                                                            onclick="document.getElementById('bulkAction').value='update_one'; document.getElementById('singleFeedbackId').value='<?php echo (int)$item['id']; ?>'; document.getElementById('singleFeedbackStatus').value=document.getElementById('singleStatus<?php echo (int)$item['id']; ?>').value;">
                                                            Guardar
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if (empty($feedback)): ?>
                                            <tr><td colspan="8">Sem feedback.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <input type="hidden" id="bulkAction" name="action" value="">
                            <input type="hidden" id="singleFeedbackId" name="feedback_id" value="">
                            <input type="hidden" id="singleFeedbackStatus" name="status" value="">

                            <div class="bulk-bar">
                                <span class="bulk-label">Selecionados:</span>
                                <select id="bulkStatusSelected" class="input-sm">
                                    <option value="pendente">pendente</option>
                                    <option value="em_analise">em_analise</option>
                                    <option value="resolvido">resolvido</option>
                                </select>
                                <button type="button" class="btn btn-primary" onclick="setBulkAction('bulk_update_selected', document.getElementById('bulkStatusSelected').value)">Atualizar selecionados</button>
                                <button type="button" class="btn-danger" onclick="confirmBulkDeleteSelected()">Apagar selecionados</button>

                                <span class="bulk-label">Todos:</span>
                                <select id="bulkStatusAll" class="input-sm">
                                    <option value="pendente">pendente</option>
                                    <option value="em_analise">em_analise</option>
                                    <option value="resolvido">resolvido</option>
                                </select>
                                <button type="button" class="btn btn-primary" onclick="setBulkAction('bulk_update_all', document.getElementById('bulkStatusAll').value)">Atualizar todos</button>
                                <button type="button" class="btn-danger" onclick="confirmDeleteResolved()">Apagar resolvidos</button>
                            </div>
                        </form>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script>
    (function () {
        const selectAll = document.getElementById('selectAllFeedback');
        const checks = document.querySelectorAll('.feedback-check');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checks.forEach((el) => { el.checked = selectAll.checked; });
            });
        }
    })();

    function setBulkAction(action, status) {
        document.getElementById('bulkAction').value = action;
        document.getElementById('singleFeedbackStatus').value = status || '';
        document.getElementById('feedbackBulkForm').submit();
    }

    function confirmBulkDeleteSelected() {
        if (!confirm('Apagar feedback selecionado?')) return;
        document.getElementById('bulkAction').value = 'delete_selected';
        document.getElementById('singleFeedbackStatus').value = '';
        document.getElementById('feedbackBulkForm').submit();
    }

    function confirmDeleteResolved() {
        if (!confirm('Apagar todos os feedback com estado resolvido?')) return;
        document.getElementById('bulkAction').value = 'delete_resolved';
        document.getElementById('singleFeedbackStatus').value = '';
        document.getElementById('feedbackBulkForm').submit();
    }
    </script>
</body>
</html>




