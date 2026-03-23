<?php
// Simulador de Investimentos
// Permite simular investimentos em Ações, ETF ou Criptomoedas
// com cálculo de juros compostos ao longo do tempo

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Dados dos tipos de investimento com informações de risco e rendimento
$tipos_investimento = [
    'acoes' => [
        'nome' => 'Ações',
        'descricao' => 'Investimento em empresas listadas na bolsa de valores',
        'risco' => 'Médio-Alto',
        'risco_nivel' => 3,
        'retorno_historico' => '8-12% ao ano',
        'volatilidade' => 'Moderada',
        'cor' => '#2563eb'
    ],
    'etf' => [
        'nome' => 'ETF',
        'descricao' => 'Fundos de índices que replicam mercados inteiros',
        'risco' => 'Médio',
        'risco_nivel' => 2,
        'retorno_historico' => '6-10% ao ano',
        'volatilidade' => 'Baixa-Moderada',
        'cor' => '#7c3aed'
    ],
    'cripto' => [
        'nome' => 'Criptomoedas',
        'descricao' => 'Moedas digitais como Bitcoin, Ethereum, etc.',
        'risco' => 'Muito Alto',
        'risco_nivel' => 5,
        'retorno_historico' => 'Variable (-50% a +200%)',
        'volatilidade' => 'Extremamente Alta',
        'cor' => '#f59e0b'
    ]
];

// Períodos disponíveis
$periodos = [
    1 => '1 Ano',
    5 => '5 Anos',
    10 => '10 Anos'
];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Investimentos - BillWise</title>
    <link rel="stylesheet" href="assets/css/estilo.css">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <link rel="stylesheet" href="assets/css/botoes.css">
    <link rel="stylesheet" href="assets/css/formularios.css">
    <link rel="stylesheet" href="assets/css/modais.css">
    <link rel="stylesheet" href="assets/css/rodape.css?v=2">
    <link rel="stylesheet" href="assets/css/painel.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;500;600;700&family=Source+Serif+4:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/barra_lateral.php'; ?>
        <div class="main-content-wrapper">
            <main class="main">
                <div class="container">
                    <!-- Hero Section -->
                    <section class="dashboard-hero">
                        <div class="hero-greeting">
                            <h1>Simulador de Investimentos</h1>
                            <p>Descubra o potencial dos seus investimentos com juros compostos</p>
                        </div>
                    </section>

                    <div class="simulador-container">
                        <!-- Calculator Card -->
                        <div class="investimento-card">
                            <div class="form-group">
                                <label for="capital" class="form-label">
                                    <svg class="label-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Capital Inicial (€)
                                </label>
                                <input type="number" id="capital" value="100" step="1" min="1" class="form-input">
                            </div>
                            
                            <!-- Tipo de Investimento -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg class="label-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Tipo de Investimento
                                </label>
                                <div class="tipo-selector">
                                    <?php foreach ($tipos_investimento as $key => $tipo): ?>
                                    <label class="tipo-opcao">
                                        <input type="radio" name="tipo" value="<?php echo $key; ?>" <?php echo $key === 'acoes' ? 'checked' : ''; ?>>
                                        <div class="tipo-card">
                                                                                    <div class="tipo-nome"><?php echo $tipo['nome']; ?></div>
                                            <div class="tipo-risco">Risco: <?php echo $tipo['risco']; ?></div>
                                        </div>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Período -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg class="label-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Período de Investimento
                                </label>
                                <div class="periodo-selector">
                                    <button type="button" class="periodo-btn" data-periodo="1">1 Ano</button>
                                    <button type="button" class="periodo-btn active" data-periodo="5">5 Anos</button>
                                    <button type="button" class="periodo-btn" data-periodo="10">10 Anos</button>
                                </div>
                                <input type="hidden" id="periodo" value="5">
                            </div>
                            
                            <div class="form-actions">
                                <button class="btn btn-primary btn-full" id="calcularBtn">
                                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Calcular Investimento
                                </button>
                            </div>
                        </div>
                        
                        <!-- Resultado -->
                        <div id="resultado" class="resultado-box">
                            <div class="resultado-header">
                                <span class="resultado-titulo">Resultado da Simulação</span>
                                <span class="resultado-tipo" id="resultadoTipo">Ações</span>
                            </div>
                            
                            <div class="resultado-valores">
                                <div class="resultado-item">
                                    <div class="resultado-label">Capital Inicial</div>
                                    <div class="resultado-value" id="resultadoInicial">€100</div>
                                </div>
                                <div class="resultado-item">
                                    <div class="resultado-label">Rendimento Total</div>
                                    <div class="resultado-value" id="resultadoRendimento">€0</div>
                                </div>
                                <div class="resultado-item">
                                    <div class="resultado-label">Valor Final</div>
                                    <div class="resultado-value destaque" id="resultadoFinal">€100</div>
                                </div>
                            </div>
                            
                            <div class="crescimento-info">
                                <div class="crescimento-titulo">
                                    <svg class="label-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Evolução do Capital
                                </div>
                                <div class="crescimento-lista" id="evolucaoLista">
                                    <!-- Preenchido via JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Educativo -->
                        <div class="educativo-card">
                            <h3 class="educativo-titulo">
                                <svg class="label-icon label-icon-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                O que precisa de saber
                            </h3>
                            
                            <div class="educativo-grid">
                                <div class="educativo-item">
                                    <h4 class="educativo-item-title risco">Risco</h4>
                                    <p>O risco representa a possibilidade de perder parte ou todo o investimento. Investimentos com maior potencial de rendimento geralmente envolvem maior risco.</p>
                                    <div class="risco-indicator">
                                        <div class="risco-barra"></div>
                                        <div class="risco-barra"></div>
                                        <div class="risco-barra"></div>
                                        <div class="risco-barra"></div>
                                        <div class="risco-barra"></div>
                                    </div>
                                </div>
                                
                                <div class="educativo-item">
                                    <h4 class="educativo-item-title rendimento">Rendimento</h4>
                                    <p>O rendimento é o lucro obtido com o investimento. Os valores apresentados são estimativas baseadas em médias históricas e podem variar.</p>
                                    <div class="risco-indicator">
                                        <div class="risco-barra ativa risco-baixo"></div>
                                        <div class="risco-barra ativa risco-baixo"></div>
                                        <div class="risco-barra ativa risco-medio"></div>
                                        <div class="risco-barra"></div>
                                        <div class="risco-barra"></div>
                                    </div>
                                </div>
                                
                                <div class="educativo-item">
                                    <h4 class="educativo-item-title juros">Juros Compostos</h4>
                                    <p>Os juros compostos são calculados sobre o capital inicial E os juros acumulados. É o "efeito bola de neve" que faz o dinheiro crescer exponencialmente ao longo do tempo.</p>
                                    <div class="risco-indicator">
                                        <div class="risco-barra ativa risco-baixo"></div>
                                        <div class="risco-barra ativa risco-baixo"></div>
                                        <div class="risco-barra ativa risco-baixo"></div>
                                        <div class="risco-barra ativa risco-medio"></div>
                                        <div class="risco-barra"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/notificacoes.js?v=1"></script>
    <script src="assets/js/principal.js"></script>
    
    <script>
        // Dados dos tipos de investimento
        const tiposInvestimento = {
            'acoes': {
                nome: 'Ações',
                retornoMin: 0.08,
                retornoMax: 0.12
            },
            'etf': {
                nome: 'ETF',
                retornoMin: 0.06,
                retornoMax: 0.10
            },
            'cripto': {
                nome: 'Criptomoedas',
                retornoMin: -0.20,
                retornoMax: 0.80
            }
        };
        
        // Elementos do DOM
        const capitalInput = document.getElementById('capital');
        const periodoInput = document.getElementById('periodo');
        const periodoBtns = document.querySelectorAll('.periodo-btn');
        const calcularBtn = document.getElementById('calcularBtn');
        const resultadoBox = document.getElementById('resultado');
        
        // Seleção de período
        periodoBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                periodoBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                periodoInput.value = btn.dataset.periodo;
            });
        });
        
        // Função de cálculo
        function calcular() {
            const capital = parseFloat(capitalInput.value) || 0;
            const periodo = parseInt(periodoInput.value);
            const tipo = document.querySelector('input[name="tipo"]:checked').value;
            
            if (capital <= 0) {
                alert('Por favor, insira um capital válido maior que 0.');
                return;
            }
            
            const tipoInfo = tiposInvestimento[tipo];
            const retornoMedio = (tipoInfo.retornoMin + tipoInfo.retornoMax) / 2;
            
            // Cálculo com juros compostos: V = P * (1 + r)^n
            const valorFinal = capital * Math.pow(1 + retornoMedio, periodo);
            const rendimentoTotal = valorFinal - capital;
            
            // AtualizarUI
            document.getElementById('resultadoTipo').textContent = tipoInfo.nome;
            document.getElementById('resultadoInicial').textContent = formatarMoeda(capital);
            document.getElementById('resultadoRendimento').textContent = formatarMoeda(rendimentoTotal);
            document.getElementById('resultadoFinal').textContent = formatarMoeda(valorFinal);
            
            // Evolução ao longo dos anos
            let evolucaoHTML = '';
            for (let ano = 1; ano <= periodo; ano++) {
                const valorAno = capital * Math.pow(1 + retornoMedio, ano);
                evolucaoHTML += `
                    <div class="crescimento-ano">
                        <div class="ano">Ano ${ano}</div>
                        <div class="valor">${formatarMoeda(valorAno)}</div>
                    </div>
                `;
            }
            document.getElementById('evolucaoLista').innerHTML = evolucaoHTML;
            
            // Mostrar resultado com animação
            resultadoBox.classList.remove('mostrar');
            setTimeout(() => {
                resultadoBox.classList.add('mostrar');
            }, 100);
        }
        
        // Formatar moeda
        function formatarMoeda(valor) {
            return '€' + valor.toLocaleString('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        
        // Event listeners
        calcularBtn.addEventListener('click', calcular);
        
        // Permitir Enter no input
        capitalInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                calcular();
            }
        });
    </script>
</body>
</html>



