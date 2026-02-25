<?php
// Pagina informativa sobre criptomoedas e ETFs (acesso publico)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criptomoedas e ETFs - BillWise</title>
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <link rel="stylesheet" href="assets/css/header.css?v=2">
    <link rel="stylesheet" href="assets/css/buttons.css?v=3">
    <link rel="stylesheet" href="assets/css/forms.css?v=3">
    <link rel="stylesheet" href="assets/css/modals.css?v=4">
    <link rel="stylesheet" href="assets/css/footer.css?v=2">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --invest-bg-1: #081525;
            --invest-bg-2: #113a7d;
            --invest-bg-3: #0f766e;
            --invest-ink: #0f172a;
            --invest-muted: #475569;
            --invest-card: #ffffff;
            --invest-border: #dbe4f0;
            --invest-ok: #166534;
            --invest-warn: #9a3412;
            --invest-soft-ok: #dcfce7;
            --invest-soft-warn: #ffedd5;
            --invest-soft-blue: #eff6ff;
        }

        .invest-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(130deg, var(--invest-bg-1) 0%, var(--invest-bg-2) 50%, var(--invest-bg-3) 100%);
            color: #fff;
            padding: 2.6rem 0 3rem;
        }

        .invest-hero::before,
        .invest-hero::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            opacity: 0.2;
            pointer-events: none;
        }

        .invest-hero::before {
            width: 280px;
            height: 280px;
            background: #38bdf8;
            top: -120px;
            right: -80px;
        }

        .invest-hero::after {
            width: 240px;
            height: 240px;
            background: #34d399;
            bottom: -120px;
            left: -100px;
        }

        .invest-hero h1 {
            font-size: 2.2rem;
            margin-bottom: 0.6rem;
        }

        .invest-hero p {
            max-width: 820px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .hero-kpis {
            margin-top: 1.2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
            max-width: 900px;
        }

        .hero-kpi {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.8rem;
            backdrop-filter: blur(4px);
        }

        .hero-kpi strong {
            display: block;
            font-size: 1.3rem;
            line-height: 1.2;
        }

        .hero-kpi span {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .section-wrap {
            padding: 1.25rem 1rem 0;
        }

        .section-wrap:last-of-type {
            padding-bottom: 3rem;
        }

        .section-title {
            max-width: 1200px;
            margin: 0 auto 0.8rem;
            font-size: 1.45rem;
            color: var(--invest-ink);
        }

        .section-sub {
            max-width: 1200px;
            margin: 0 auto 1.1rem;
            color: var(--invest-muted);
        }

        .invest-grid,
        .portfolio-grid,
        .myths-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            gap: 0.9rem;
        }

        .invest-grid,
        .portfolio-grid {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .myths-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .invest-card,
        .portfolio-card,
        .myth-card,
        .checklist-box,
        .compare-table {
            background: var(--invest-card);
            border: 1px solid var(--invest-border);
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.07);
        }

        .invest-card,
        .portfolio-card,
        .myth-card,
        .checklist-box {
            padding: 1rem;
        }

        .invest-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .invest-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.1);
        }

        .invest-card h2,
        .portfolio-card h3 {
            margin: 0 0 0.5rem;
            color: var(--invest-ink);
        }

        .invest-card p,
        .portfolio-card p,
        .myth-card p {
            margin: 0;
            color: var(--invest-muted);
            line-height: 1.55;
        }

        .invest-list {
            margin: 0.75rem 0 0;
            padding-left: 1.1rem;
            color: #334155;
            line-height: 1.5;
        }

        .compare-table {
            max-width: 1200px;
            margin: 1rem auto 0;
            overflow: hidden;
        }

        .compare-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .compare-table th,
        .compare-table td {
            padding: 0.85rem 1rem;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .compare-table th {
            background: #f8fafc;
            color: var(--invest-ink);
            font-weight: 700;
        }

        .alloc-list {
            margin-top: 0.75rem;
        }

        .alloc-item {
            margin-bottom: 0.6rem;
        }

        .alloc-item strong {
            display: block;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .alloc-bar {
            height: 9px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .alloc-fill {
            height: 100%;
            background: linear-gradient(90deg, #1d4ed8, #14b8a6);
        }

        .myth-card strong {
            display: block;
            color: #991b1b;
            margin-bottom: 0.4rem;
        }

        .fact {
            margin-top: 0.7rem;
            padding: 0.7rem;
            border-radius: 10px;
            background: var(--invest-soft-blue);
            color: #1e3a8a;
            font-size: 0.93rem;
        }

        .checklist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 0.7rem;
            margin-top: 0.8rem;
        }

        .check-item {
            border-radius: 10px;
            padding: 0.65rem 0.8rem;
            background: var(--invest-soft-ok);
            color: var(--invest-ok);
            border: 1px solid #bbf7d0;
            font-size: 0.93rem;
        }

        .invest-note {
            max-width: 1200px;
            margin: 1rem auto 0;
            background: var(--invest-soft-warn);
            border: 1px solid #fed7aa;
            border-radius: 12px;
            color: var(--invest-warn);
            padding: 0.9rem 1rem;
        }

        .invest-actions {
            max-width: 1200px;
            margin: 1.1rem auto 0;
            display: flex;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .invest-hero h1 {
                font-size: 1.75rem;
            }

            .compare-table {
                overflow-x: auto;
            }

            .compare-table table {
                min-width: 680px;
            }
        }
    </style>
</head>
<body>
    <?php include 'php/header.php'; ?>

    <main id="main" class="main">
        <section class="invest-hero">
            <div class="container">
                <h1>Criptomoedas e ETFs</h1>
                <p>Um guia pratico para comparares risco, diversificacao e objetivos. Esta pagina ajuda-te a decidir com mais clareza antes de investires.</p>
                <div class="hero-kpis">
                    <div class="hero-kpi">
                        <strong>24/7</strong>
                        <span>Cripto negoceia sem pausa</span>
                    </div>
                    <div class="hero-kpi">
                        <strong>Diversificacao</strong>
                        <span>ETFs podem incluir dezenas ou centenas de ativos</span>
                    </div>
                    <div class="hero-kpi">
                        <strong>Gestao de risco</strong>
                        <span>Sem plano, qualquer estrategia fica fragil</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-wrap">
            <h2 class="section-title">Conceitos base para comecar bem</h2>
            <p class="section-sub">Antes de olhar para rentabilidade, confirma se entendes como cada classe funciona no dia a dia.</p>
            <div class="invest-grid">
                <article class="invest-card">
                    <h2>O que sao criptomoedas?</h2>
                    <p>Ativos digitais baseados em blockchain. O preco costuma oscilar muito e a variacao pode ser brusca.</p>
                    <ul class="invest-list">
                        <li>Mercado aberto 24/7.</li>
                        <li>Alta volatilidade no curto prazo.</li>
                        <li>Risco operacional (custodia e seguranca).</li>
                    </ul>
                </article>

                <article class="invest-card">
                    <h2>O que sao ETFs?</h2>
                    <p>Fundos negociados em bolsa que podem seguir indices, setores ou materias-primas com uma unica compra.</p>
                    <ul class="invest-list">
                        <li>Diversificacao imediata.</li>
                        <li>Custos normalmente baixos.</li>
                        <li>Negociados em horario de bolsa.</li>
                    </ul>
                </article>

                <article class="invest-card">
                    <h2>Como avaliar risco</h2>
                    <p>Antes de investir, define horizonte temporal, objetivo e perda maxima que consegues aceitar sem vender em panico.</p>
                    <ul class="invest-list">
                        <li>Reserva de emergencia primeiro.</li>
                        <li>Nao investir dinheiro de curto prazo.</li>
                        <li>Diversificar em vez de concentrar.</li>
                    </ul>
                </article>
            </div>
        </section>

        <section class="section-wrap">
            <h2 class="section-title">Comparacao direta</h2>
            <p class="section-sub">Usa esta tabela para perceber em que pontos cada opcao exige mais disciplina.</p>
            <div class="compare-table">
                <table>
                    <thead>
                        <tr>
                            <th>Caracteristica</th>
                            <th>Criptomoedas</th>
                            <th>ETFs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Volatilidade</td>
                            <td>Alta a muito alta</td>
                            <td>Baixa a media (depende do ETF)</td>
                        </tr>
                        <tr>
                            <td>Diversificacao</td>
                            <td>Normalmente baixa por ativo</td>
                            <td>Alta (cesto de ativos)</td>
                        </tr>
                        <tr>
                            <td>Horarios de negociacao</td>
                            <td>24 horas por dia</td>
                            <td>Horario da bolsa</td>
                        </tr>
                        <tr>
                            <td>Nivel de complexidade</td>
                            <td>Medio a alto</td>
                            <td>Baixo a medio</td>
                        </tr>
                        <tr>
                            <td>Liquidez e acesso</td>
                            <td>Muito facil em corretoras cripto</td>
                            <td>Depende da corretora e do mercado</td>
                        </tr>
                        <tr>
                            <td>Curva de aprendizagem</td>
                            <td>Carteiras, redes e seguranca</td>
                            <td>Escolha de indice, TER e exposicao</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="section-wrap">
            <h2 class="section-title">Mitos comuns</h2>
            <p class="section-sub">Evita erros de principiante ao separar promessas de realidade.</p>
            <div class="myths-grid">
                <article class="myth-card">
                    <strong>"Cripto sobe sempre no longo prazo."</strong>
                    <p>Mesmo ativos fortes passam anos de quedas acentuadas.</p>
                    <div class="fact">Fato: define limite de perda e rebalanceia periodicamente.</div>
                </article>
                <article class="myth-card">
                    <strong>"Qualquer ETF e automaticamente seguro."</strong>
                    <p>Existem ETFs setoriais e alavancados com risco elevado.</p>
                    <div class="fact">Fato: confirma indice, custos (TER) e concentracao do fundo.</div>
                </article>
                <article class="myth-card">
                    <strong>"So preciso escolher bem a entrada."</strong>
                    <p>Sem estrategia de saida, ganhos evaporam em mercados volateis.</p>
                    <div class="fact">Fato: define objetivo, prazo e regras de realizacao antes de comprar.</div>
                </article>
            </div>
        </section>

        <section class="section-wrap">
            <h2 class="section-title">Checklist antes de investir</h2>
            <p class="section-sub">Se falhares em um ponto, melhora o plano antes de colocar dinheiro.</p>
            <div class="checklist-box">
                <div class="checklist-grid">
                    <div class="check-item">Tenho fundo de emergencia separado.</div>
                    <div class="check-item">Sei quanto posso perder sem comprometer contas.</div>
                    <div class="check-item">Tenho horizonte temporal definido.</div>
                    <div class="check-item">Entendo onde estou a investir e os custos envolvidos.</div>
                    <div class="check-item">Nao concentro tudo num unico ativo.</div>
                    <div class="check-item">Tenho rotina de revisao mensal ou trimestral.</div>
                </div>
            </div>

            <div class="invest-note">
                Este conteudo e educativo e nao constitui recomendacao financeira.
            </div>

            <div class="invest-actions">
                <a class="btn btn-primary" href="index.php">Voltar ao inicio</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="btn btn-outline" href="dashboard.php">Ir para o painel</a>
                <?php else: ?>
                    <button class="btn btn-outline open-login">Entrar para guardar a tua evolucao</button>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'php/footer.php'; ?>
    <script src="assets/js/notifications.js?v=1"></script>
    <script src="assets/js/main.js?v=3"></script>
</body>
</html>
