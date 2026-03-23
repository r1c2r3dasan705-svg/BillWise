console.log('*** MAIN.JS LOADED! ***');
// JavaScript Principal do BillWise
// Gere modais, formulários e interações das páginas

// Funções auxiliares para abrir e fechar modais
function showModal(modal) {
    if (!modal) return;
    modal.classList.add('active');
}

function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('active');
}

// Aguardar carregamento completo do DOM
document.addEventListener('DOMContentLoaded', function() {

console.log('BillWise JS loaded');

// AUTENTICAÇÃO - Gestão de modais de login/registo
const authModal = document.getElementById('auth-modal');
const openLoginBtns = document.querySelectorAll('.open-login');
const authCloseBtn = document.getElementById('auth-close');
const showRegisterBtn = document.getElementById('show-register');
const showLoginBtn = document.getElementById('show-login');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const authTitle = document.getElementById('auth-title');

// Abrir modal de login ao clicar nos botões
if (openLoginBtns && openLoginBtns.length) {
    openLoginBtns.forEach(btn => btn.addEventListener('click', (e) => {
        if (e && typeof e.preventDefault === 'function') e.preventDefault();
        showModal(authModal);
        // Focar no campo email para facilitar navegação por teclado
        const emailInput = document.getElementById('email');
        if (emailInput) setTimeout(() => emailInput.focus(), 50);
    }));
}

// Menu mobile - alternar visibilidade
    const menuBtns = document.querySelectorAll('.menu-btn');
    if (menuBtns && menuBtns.length) {
        menuBtns.forEach(btn => btn.addEventListener('click', (e) => {
            const nav = e.currentTarget.closest('.nav-container')?.querySelector('.nav');
            if (nav) nav.classList.toggle('open');
        }));
    }

if (authCloseBtn) authCloseBtn.addEventListener('click', () => closeModal(authModal));

if (showRegisterBtn) {
    showRegisterBtn.addEventListener('click', () => {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        authTitle.textContent = 'Registar';
    });
}

if (showLoginBtn) {
    showLoginBtn.addEventListener('click', () => {
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
        authTitle.textContent = 'Entrar';
    });
}

// Submissão do formulário de login
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            // Enviar credenciais para o servidor
            const res = await fetch('php/entrar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ email, senha: password })
            });
            const data = await res.json();
            if (data.success) {
                // Redirecionar para o painel após login bem-sucedido
                closeModal(authModal);
window.location.href = 'painel.php';
            } else {
                Notifications.error(data.message, 'Erro ao Entrar');
            }
        } catch (err) {
            console.error(err);
            Notifications.error('Não foi possível contactar o servidor. Verifique a sua conexão.', 'Erro de Conexão');
        }
    });
}

// Submissão do formulário de registo
if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('nome').value;
        const email = document.getElementById('reg-email').value;
        const password = document.getElementById('reg-password').value;

        try {
            // Criar nova conta no servidor
            const res = await fetch('php/registar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ nome: name, email, senha: password })
            });
            const data = await res.json();
            if (data.success) {
                Notifications.success('Conta criada com sucesso! Agora pode fazer login.', 'Bem-vindo!');
                // Alternar para o formulário de login
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
                authTitle.textContent = 'Entrar';
            } else {
                Notifications.error(data.message, 'Erro ao Criar Conta');
            }
        } catch (err) {
            console.error(err);
            Notifications.error('Não foi possível contactar o servidor. Verifique a sua conexão.', 'Erro de Conexão');
        }
    });
}

// ========================================
// PASSWORD RECOVERY - Recuperação de password no modal
// ========================================

// Elementos do formulário de recuperação
const showRecoverBtn = document.getElementById('show-recover');
            const recoverEmailForm = document.getElementById('recover-email-form');
            window.recoverEmailGlobal = '';

const recoverCodeForm = document.getElementById('recover-code-form');
// Removed DEBUG log
const recoverSuccess = document.getElementById('recover-success');
const backToLoginBtn = document.getElementById('back-to-login');
const backToRecoverEmailBtn = document.getElementById('back-to-recover-email');
const goToLoginBtn = document.getElementById('go-to-login');

// Variável para guardar o email durante a recuperação
let recoverEmail = '';

// Função para mostrar o formulário de recuperação de password
function showRecoverForm() {
    loginForm.style.display = 'none';
    registerForm.style.display = 'none';
    recoverEmailForm.style.display = 'block';
    recoverCodeForm.style.display = 'none';
    recoverSuccess.style.display = 'none';
    authTitle.textContent = 'Recuperar Password';
}

// Função para voltar ao formulário de login
function showLoginFromRecover() {
    loginForm.style.display = 'block';
    registerForm.style.display = 'none';
    recoverEmailForm.style.display = 'none';
    recoverCodeForm.style.display = 'none';
    recoverSuccess.style.display = 'none';
    authTitle.textContent = 'Entrar';
    
    // Limpar campos
    document.getElementById('recover-email').value = '';
    document.getElementById('recover-nova-senha').value = '';
    document.getElementById('recover-confirmar-senha').value = '';
    document.querySelectorAll('.code-input-modal').forEach(input => input.value = '');
}

// Event listener para o botão "Esqueci a password"
if (showRecoverBtn) {
    showRecoverBtn.addEventListener('click', (e) => {
        e.preventDefault();
        showRecoverForm();
    });
}

// Voltar ao login
if (backToLoginBtn) {
    backToLoginBtn.addEventListener('click', () => {
        showLoginFromRecover();
    });
}

// Voltar ao formulário de email
if (backToRecoverEmailBtn) {
    backToRecoverEmailBtn.addEventListener('click', () => {
        recoverCodeForm.style.display = 'none';
        recoverEmailForm.style.display = 'block';
        authTitle.textContent = 'Recuperar Password';
    });
}

// Voltar ao login após sucesso
if (goToLoginBtn) {
    goToLoginBtn.addEventListener('click', () => {
        showLoginFromRecover();
    });
}

// Submissão do formulário de email para recuperação
if (recoverEmailForm) {
    recoverEmailForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.getElementById('recover-email').value;
        recoverEmail = email;
        window.recoverEmailGlobal = email;
// Removed DEBUG log: console.log('EMAIL SET:', window.recoverEmailGlobal);

        
        try {
            const response = await fetch('php/esqueci_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Passar para o passo do código
                recoverEmailForm.style.display = 'none';
                recoverCodeForm.style.display = 'block';
                authTitle.textContent = 'Verificar Código';
                
                // Limpar inputs do código
                document.querySelectorAll('.code-input-modal').forEach(input => input.value = '');
                document.getElementById('recover-codigo').value = '';
                
                // Focar no primeiro input do código
                document.querySelector('.code-input-modal[data-index="0"]').focus();
            } else {
                Notifications.error(result.error || 'Erro ao enviar código', 'Erro');
            }
        } catch (error) {
            console.error(error);
            Notifications.error('Erro ao comunicar com o servidor', 'Erro');
        }
    });
}

// Código input - auto focus e paste
const codeInputsModal = document.querySelectorAll('.code-input-modal');
codeInputsModal.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        if (e.target.value) {
            if (index < 5) {
                codeInputsModal[index + 1].focus();
            }
        }
        const code = Array.from(codeInputsModal).map(i => i.value).join('');
        document.getElementById('recover-codigo').value = code;
    });
    
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
            codeInputsModal[index - 1].focus();
        }
    });
    
    input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasteData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
        pasteData.split('').forEach((char, i) => {
            if (codeInputsModal[i]) {
                codeInputsModal[i].value = char;
            }
        });
        const code = Array.from(codeInputsModal).map(i => i.value).join('');
        document.getElementById('recover-codigo').value = code;
    });
});

// Submissão do formulário de código e nova password
// Removed DEBUG log
if (recoverCodeForm) {
    recoverCodeForm.addEventListener('submit', async (e) => {
// Removed DEBUG log
        e.preventDefault();
        
        const codigo = document.getElementById('recover-codigo').value;
        const novaSenha = document.getElementById('recover-nova-senha').value;
        const confirmarSenha = document.getElementById('recover-confirmar-senha').value;
        
        if (codigo.length !== 6) {
            Notifications.error('Por favor, introduza o código completo de 6 dígitos', 'Código Inválido');
            return;
        }
        
        if (novaSenha !== confirmarSenha) {
            Notifications.error('As passwords não coincidem', 'Erro');
            return;
        }
        
        if (novaSenha.length < 6) {
            Notifications.error('A password deve ter pelo menos 6 caracteres', 'Erro');
            return;
        }
        
        try {
// Removed DEBUG log
            const response = await fetch('php/redefinir_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({
                    email: recoverEmail,
                    codigo: codigo,
                    nova_senha: novaSenha
                })
            });
            console.log('DEBUG FETCH: Response status:', response.status);
            console.log('DEBUG FETCH: Response status:', response.status);
            const responseText = await response.text();
            console.log('DEBUG FETCH: Raw response:', responseText);
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseErr) {
                console.error('JSON parse error:', parseErr);
                result = { success: false, error: 'Invalid JSON response' };
            }
            
            if (result.success) {
                // Mostrar sucesso
                recoverCodeForm.style.display = 'none';
                recoverSuccess.style.display = 'block';
                authTitle.textContent = '';
            } else {
                Notifications.error(result.error || 'Erro ao alterar password', 'Erro');
            }
        } catch (error) {
            console.error(error);
            Notifications.error('Erro ao comunicar com o servidor', 'Erro');
        }
    });
}

// GESTÃO DE ACESSO RESTRITO - Modal para utilizadores não autenticados
const restrictedModal = document.getElementById('restricted-modal');
const restrictedCloseBtn = document.getElementById('restricted-close');
const restrictedLoginBtn = document.getElementById('restricted-login');
const restrictedRegisterBtn = document.getElementById('restricted-register');

console.log('Restricted modal element:', restrictedModal);

// Adicionar event listeners aos botões que abrem o modal de acesso restrito
const openRestrictedBtns = document.querySelectorAll('.open-restricted');
console.log('Found', openRestrictedBtns.length, 'open-restricted buttons');
if (openRestrictedBtns && openRestrictedBtns.length) {
        openRestrictedBtns.forEach(btn => btn.addEventListener('click', (e) => {
        e.preventDefault();
        showModal(restrictedModal);
    }));
}

if (restrictedCloseBtn) {
    restrictedCloseBtn.addEventListener('click', () => closeModal(restrictedModal));
}

if (restrictedLoginBtn) {
    restrictedLoginBtn.addEventListener('click', () => {
        closeModal(restrictedModal);
        showModal(authModal);
        // Mostrar formulário de login
        if (loginForm && registerForm && authTitle) {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            authTitle.textContent = 'Entrar';
        }
        // focar no campo email para facilitar navegação por teclado
        const emailInput = document.getElementById('email');
        if (emailInput) setTimeout(() => emailInput.focus(), 50);
    });
}

if (restrictedRegisterBtn) {
    restrictedRegisterBtn.addEventListener('click', () => {
        closeModal(restrictedModal);
        showModal(authModal);
        // Mostrar formulário de registo
        if (loginForm && registerForm && authTitle) {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            authTitle.textContent = 'Registar';
        }
        // focar no campo nome para facilitar navegação por teclado
        const nomeInput = document.getElementById('nome');
        if (nomeInput) setTimeout(() => nomeInput.focus(), 50);
    });
}

//Fechar modais ao clicar fora do conteúdo
window.addEventListener('click', (e) => {
    if (e.target === authModal) closeModal(authModal);
    if (e.target === restrictedModal) closeModal(restrictedModal);
    const expenseModal = document.getElementById('expense-modal');
    if (expenseModal && e.target === expenseModal) closeModal(expenseModal);
});

// CALCULADORA PPR - Plano Poupança Reforma
const calcBtn = document.getElementById('calcPpr');
if (calcBtn) {
    calcBtn.addEventListener('click', () => {
        // Obter valores dos campos
        const initial = parseFloat(document.getElementById('initial').value) || 0;
        const contrib = parseFloat(document.getElementById('contrib').value) || 0;
        const years = parseFloat(document.getElementById('years').value) || 0;
        const rate = parseFloat(document.getElementById('rate').value) || 0;

        // Validar entrada
        if (years <= 0) {
            Notifications.warning('Por favor, insira um número válido de anos.', 'Dados Inválidos');
            return;
        }

        // Calcular juros compostos
        const monthlyRate = rate / 100 / 12;
        const months = years * 12;
        const totalContributed = initial + (contrib * months);

        // Fórmula de valor futuro: FV = PV(1+r)^n + PMT * [((1+r)^n - 1) / r]
        let futureValue = initial * Math.pow(1 + monthlyRate, months);
        if (monthlyRate === 0) {
            futureValue += contrib * months;
        } else {
            futureValue += contrib * (Math.pow(1 + monthlyRate, months) - 1) / monthlyRate;
        }

        const totalInterest = futureValue - totalContributed;
        const returnPercentage = totalContributed > 0 ? ((totalInterest / totalContributed) * 100) : 0;

        // Exibir resultado formatado
        const resultEl = document.getElementById('ppr-result');
        if (resultEl) {
            resultEl.style.display = 'block';
            resultEl.innerHTML = `
                <div style="text-align: center;">
                    <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem; font-weight: 700;">€${futureValue.toFixed(2).replace('.', ',')}</h2>
                    <p style="font-size: 1.1rem; opacity: 0.95; margin-bottom: 2rem;">Valor estimado da sua reforma</p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid rgba(255,255,255,0.2);">
                        <div>
                            <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 0.25rem;">Total Investido</p>
                            <p style="font-size: 1.3rem; font-weight: 600;">€${totalContributed.toFixed(2).replace('.', ',')}</p>
                        </div>
                        <div>
                            <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 0.25rem;">Juros Ganhos</p>
                            <p style="font-size: 1.3rem; font-weight: 600;">€${totalInterest.toFixed(2).replace('.', ',')}</p>
                        </div>
                    </div>
                    <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
                        <p style="font-size: 0.9rem; margin-bottom: 0.25rem;">Retorno Total: <strong>${returnPercentage.toFixed(1)}%</strong></p>
                        <p style="font-size: 0.85rem; opacity: 0.9;">Em ${years} anos com ${rate}% de retorno anual</p>
                    </div>
                </div>
            `;
            resultEl.scrollIntoView({ behavior: 'smooth' });
        }
    });
}

// ORÇAMENTOS - Criar novos limites de gastos
const budgetForm = document.getElementById('budget-form');
if (budgetForm) {
    budgetForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const limit = parseFloat(document.getElementById('limit').value);
        
        // Enviar para o servidor
        try {
            const res = await fetch('php/guardar_orcamento.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ nome: name, limite: limit })
            });
            const json = await res.json();
            if (json.success) {
                renderBudgets();
                budgetForm.reset();
                Notifications.success('Orçamento guardado com sucesso!', 'Guardado');
                setTimeout(() => window.location.reload(), 1000);
                return;
            }
            Notifications.error(json.message || 'Erro ao guardar o orçamento.', 'Erro');
        } catch (err) {
            console.warn('Servidor não disponível.');
            Notifications.error('Servidor indisponível. Tente novamente.', 'Erro');
        }
    });
}
async function renderBudgets() {
    const budgetList = document.getElementById('budget-list');
    if (!budgetList) return;
    let budgets = [];
    // Procurar orçamentos no servidor
    try {
        const res = await fetch('php/obter_orcamentos.php', { credentials: 'same-origin' });
        const json = await res.json();
        if (json.success && Array.isArray(json.items)) {
            budgets = json.items.map(b => ({ id: b.id, name: b.nome, limit: parseFloat(b.limite), spent: parseFloat(b.gasto || 0) }));
        } else {
            Notifications.error(json.message || 'Nao foi possivel carregar os orcamentos.', 'Erro');
        }
    } catch (err) {
        Notifications.error('Servidor indisponivel. Nao foi possivel carregar os orcamentos.', 'Erro');
    }
    budgetList.innerHTML = '';
        budgets.forEach(b => {
        const div = document.createElement('div');
        div.className = 'category-item';
        div.innerHTML = `
            <div class="category-header">
                <h3 class="category-name">${b.name}</h3>
                <p class="category-limit">€${b.limit.toFixed(2)}</p>
            </div>
                <div class="progress-bar">
                <div class="progress-fill green" style="width:${b.limit > 0 ? Math.min((b.spent/b.limit)*100,100) : 0}%"></div>
            </div>
            <div class="category-details">
                <p class="category-spent">Gasto: €${b.spent.toFixed(2)}</p>
                <p class="category-remaining positive">Restante: €${(b.limit - b.spent).toFixed(2)}</p>
            </div>
            <div class="category-actions">
                <button class="btn-icon btn-edit" data-id="${b.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
                <button class="btn-icon btn-danger" data-id="${b.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </div>
        `;
        budgetList.appendChild(div);
        
        // Edit handler
        div.querySelector('.btn-edit').addEventListener('click', () => {
            // Populate form with budget data
            document.getElementById('name').value = b.name;
            document.getElementById('limit').value = b.limit;
            
            // Guardar ID do orçamento a editar no dataset do formulário
            budgetForm.dataset.editId = b.id;
            
            // Scroll para o formulário e focar no campo nome
            budgetForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.getElementById('name').focus();
        });
        
        // Apagar handler
        div.querySelector('.btn-danger').addEventListener('click', async () => {
            ConfirmModal.delete(
                'Tem certeza que deseja eliminar este orçamento?',
                async () => {
                    // Tentar eliminar do servidor primeiro
                    try {
                        const res = await fetch('php/apagar_orcamento.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            credentials: 'same-origin',
                            body: JSON.stringify({ budget_id: b.id })
                        });
                        const json = await res.json();
                        if (json.success) {
                            Notifications.success('Orçamento eliminado com sucesso!', 'Eliminado');
                            setTimeout(() => window.location.reload(), 800);
                            return;
                        }
                    } catch (err) {
                        console.warn('Erro ao eliminar do servidor, a usar localStorage');
                    }
                    
                    // Fallback para eliminar usando localStorage
                    const budgetList = JSON.parse(localStorage.getItem('budgets') || '[]');
                    const newList = budgetList.filter(budget => budget.id !== b.id);
                    localStorage.setItem('budgets', JSON.stringify(newList));
                    renderBudgets();
                    Notifications.success('Orçamento eliminado com sucesso!', 'Eliminado');
                }
            );
        });
    });
}

// Página de despesas - Adicionar nova despesa
const openAddExpenseBtn = document.getElementById('open-add-expense');
const expenseModal = document.getElementById('expense-modal');
const expenseForm = document.getElementById('expense-form');
const expenseItems = document.getElementById('expense-items') || document.querySelector('.expense-items');

if (openAddExpenseBtn) {
    openAddExpenseBtn.addEventListener('click', () => {
        // Resetar formulário e remover qualquer ID de edição anterior
        expenseForm.reset();
        delete expenseForm.dataset.editId;
        const modalTitle = expenseModal.querySelector('.modal-header h2');
        modalTitle.textContent = 'Adicionar Despesa';
        showModal(expenseModal);
    });
}

// Fechar modal de despesa ao clicar no botão de fechar
document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        const modal = e.target.closest('.modal');
        closeModal(modal);
    });
});

if (expenseForm) {
    expenseForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const amount = parseFloat(document.getElementById('amount').value);
        const category = document.getElementById('category').value;
        const date = document.getElementById('date').value;
        const description = document.getElementById('description').value;
        
        const editId = expenseForm.dataset.editId;
        const isEditing = !!editId;

        // Tentar enviar para o servidor
        try {
            const endpoint = isEditing ? 'php/atualizar_despesa.php' : 'php/adicionar_despesa.php';
            const payload = isEditing 
                ? { id: editId, valor: amount, categoria: category, data: date, descricao: description }
                : { valor: amount, categoria: category, data: date, descricao: description };
                
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (json.success) {
                const filterValue = document.getElementById('category-filter')?.value || '';
                renderExpenses(filterValue);
                expenseForm.reset();
                delete expenseForm.dataset.editId;
                closeModal(expenseModal);
                
                Notifications.success(isEditing ? 'Despesa atualizada com sucesso!' : 'Despesa guardada com sucesso!', 'Guardado');
                setTimeout(() => window.location.reload(), 1000);
                return;
            }
            Notifications.error(json.message || 'Erro ao guardar a despesa.', 'Erro');
        } catch (err) {
            console.warn('Servidor não disponível.');
            Notifications.error('Servidor indisponível. Tente novamente.', 'Erro');
        }
    });
}


async function renderExpenses(filterCategory = '') {
    const items = document.getElementById('expense-items') || expenseItems;
    if (!items) return;
    let expenses = [];
    // Procurar despesas no servidor
    try {
        const res = await fetch('php/obter_despesas.php', { credentials: 'same-origin' });
        const json = await res.json();
        if (json.success && Array.isArray(json.items)) {
            expenses = json.items.map(e => ({ id: e.id, amount: parseFloat(e.valor), category: e.categoria, date: e.data, description: e.descricao }));
        } else {
            Notifications.error(json.message || 'Nao foi possivel carregar as despesas.', 'Erro');
        }
    } catch (err) {
        Notifications.error('Servidor indisponivel. Nao foi possivel carregar as despesas.', 'Erro');
    }
    
    // Aplicar filtro de categoria se selecionado
    if (filterCategory) {
        expenses = expenses.filter(e => e.category === filterCategory);
    }
    
    items.innerHTML = '';

    if (expenses.length === 0) {
        items.innerHTML = '<p class="no-expenses">Nenhuma despesa encontrada.</p>';
        return;
    }

    expenses.forEach(exp => {
        const div = document.createElement('div');
        div.className = 'expense-item';
        div.innerHTML = `
            <div class="expense-details">
                <p class="expense-amount">€${exp.amount.toFixed(2)}</p>
                <p class="expense-category">${exp.category} - ${exp.description}</p>
                <p class="expense-date">${new Date(exp.date).toLocaleDateString('pt-PT')}</p>
            </div>
            <div class="expense-actions">
                <button class="btn-icon btn-edit" data-id="${exp.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
                <button class="btn-icon btn-danger" data-id="${exp.id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            </div>
        `;
        items.appendChild(div);

        // Editar handler
        div.querySelector('.btn-edit').addEventListener('click', () => {
            // Preencher formulário com dados da despesa
            document.getElementById('amount').value = exp.amount;
            document.getElementById('category').value = exp.category;
            document.getElementById('date').value = exp.date;
            document.getElementById('description').value = exp.description;
            
            // Mudar título do modal para indicar que estamos a editar uma despesa
            const modalTitle = expenseModal.querySelector('.modal-header h2');
            modalTitle.textContent = 'Editar Despesa';
            
            // Guardar ID da despesa a editar no dataset do formulário para usar na submissão
            expenseForm.dataset.editId = exp.id;
            
            showModal(expenseModal);
        });

        // Apagar handler
        div.querySelector('.btn-danger').addEventListener('click', async () => {
            ConfirmModal.delete(
                'Tem certeza que deseja eliminar esta despesa?',
                async () => {
                    // Tentar eliminar do servidor primeiro
                    try {
                        const res = await fetch('php/apagar_despesa.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            credentials: 'same-origin',
                            body: JSON.stringify({ expense_id: exp.id })
                        });
                        const json = await res.json();
                        if (json.success) {
                            Notifications.success('Despesa eliminada com sucesso!', 'Eliminado');
                            setTimeout(() => window.location.reload(), 800);
                            return;
                        }
                    } catch (err) {
                        console.warn('Erro ao eliminar do servidor, a usar localStorage');
                    }
                    
                    // Fallback para eliminar usando localStorage
                    const expList = JSON.parse(localStorage.getItem('expenses') || '[]');
                    const newList = expList.filter(e => e.id !== exp.id);
                    localStorage.setItem('expenses', JSON.stringify(newList));
                    renderExpenses(filterCategory);
                    Notifications.success('Despesa eliminada com sucesso!', 'Eliminado');
                }
            );
        });
    });
}

// Inicializar renderização de orçamentos e despesas
renderBudgets();
renderExpenses();

// Adicionar listener ao filtro de categoria
const categoryFilter = document.getElementById('category-filter');
if (categoryFilter) {
    categoryFilter.addEventListener('change', (e) => {
        renderExpenses(e.target.value);
    });
}

// Navegação - Destacar link ativo
const loc = window.location.pathname.split('/').pop();
if (loc) {
    document.querySelectorAll('.nav-link').forEach(a => {
        if (a.getAttribute('href') === loc) {
            a.classList.add('active');
        }
    });
}

// Fechar menu mobile ao clicar num link
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        const nav = link.closest('.nav-container').querySelector('.nav');
        if (nav && nav.classList.contains('open')) nav.classList.remove('open');
    });
});

// Formulário de newsletter - Feedback visual ao subscrever
const newsletterForm = document.getElementById('newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const input = newsletterForm.querySelector('input[type="email"]');
        const button = newsletterForm.querySelector('button');
        const originalText = button.textContent;
        
        // Mostrar feedback de subscrição bem-sucedida
        button.textContent = '? Subscrito!';
        button.style.backgroundColor = 'var(--success)';
        input.disabled = true;
        
        //Resetar formulário após 2 segundos
        setTimeout(() => {
            input.value = '';
            input.disabled = false;
            button.textContent = originalText;
            button.style.backgroundColor = '';
        }, 2000);
    });
}

// Scroll suave para âncoras internas, exceto para modais de login/registo
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '#login' && href !== '#register') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});

// ANIMAÇÕES - Usar Intersection Observer para animar elementos ao aparecerem no viewport
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Adicionar animação a cards e estatísticas
document.querySelectorAll('.action-card, .stat-card, .stat-inline').forEach((el, index) => {
    el.style.opacity = '0';
    el.style.animationDelay = `${index * 0.1}s`;
    observer.observe(el);
});

// Scroll suave e sombra no header ao rolar a página
const header = document.querySelector('.header');
let lastScrollTop = 0;
if (header) {
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollTop > 50) {
            header.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.boxShadow = 'var(--shadow-sm)';
            header.style.backdropFilter = 'none';
        }
        lastScrollTop = scrollTop;
    });
}

// Adicionar hover suave aos links do footer
const footerLinks = document.querySelectorAll('.footer-column a');
if (footerLinks && footerLinks.length) {
    footerLinks.forEach(link => {
        link.addEventListener('mouseenter', function() { this.style.color = 'var(--primary)'; });
        link.addEventListener('mouseleave', function() { this.style.color = 'var(--gray-400)'; });
    });
}

// Contador animado para números das estatísticas
function animateCounter(element, target, duration = 2000) {
    let current = 0;
    const increment = target / (duration / 16);
    const interval = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(interval);
        }
        element.textContent = Math.floor(current).toLocaleString();
    }, 16);
}

// Animar números das estatísticas quando aparecerem no viewport
const statNumbers = document.querySelectorAll('.stat-number');
let hasAnimated = false;

const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !hasAnimated) {
            hasAnimated = true;
            document.querySelectorAll('.stat-number').forEach(el => {
                const text = el.textContent;
                const match = text.match(/(\d+)/);
                if (match) {
                    const target = parseInt(match[1]);
                    animateCounter(el, target);
                }
            });
        }
    });
}, { threshold: 0.5 });

document.querySelector('.hero-stats-inline')?.querySelectorAll('.stat-number').forEach(el => {
    statsObserver.observe(el);
});

// Animação de entrada para os cards do hero
document.querySelectorAll('.hero-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.animation = `fadeInUp ${0.6 + index * 0.2}s ease-out forwards`;
});

// Adicionar hover suave aos cards do hero
document.querySelectorAll('.hero-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-12px) scale(1.02)';
    });
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

}); // Fim do DOMContentLoaded

// ========================================
// ?? ANIMAÇÕES CATCHY - Animações de Scroll
// ========================================

// Intersection Observer para animações ao scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const animateOnScroll = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animated');
            // Opcional: desconectar após animar uma vez
            animateOnScroll.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observar todos os elementos que devem animar
document.addEventListener('DOMContentLoaded', () => {
    // Animar feature cards
    document.querySelectorAll('.feature-card').forEach((card, index) => {
        card.classList.add('animate-on-scroll');
        card.style.animationDelay = `${index * 0.1}s`;
        animateOnScroll.observe(card);
    });

    // Animar stat items
    document.querySelectorAll('.stat-item').forEach((stat, index) => {
        stat.classList.add('animate-on-scroll');
        stat.style.animationDelay = `${index * 0.15}s`;
        animateOnScroll.observe(stat);
    });

    // Animar benefit items
    document.querySelectorAll('.benefit-card').forEach((benefit, index) => {
        benefit.classList.add('animate-on-scroll');
        benefit.style.animationDelay = `${index * 0.1}s`;
        animateOnScroll.observe(benefit);
    });

    // Efeito parallax suave no hero
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const scrolled = window.pageYOffset;
                const hero = document.querySelector('.hero');
                if (hero && scrolled < 800) {
                    hero.style.transform = `translateY(${scrolled * 0.3}px)`;
                    hero.style.opacity = 1 - (scrolled / 800);
                }
                ticking = false;
            });
            ticking = true;
        }
    });

    // Header com sombra ao scroll
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.boxShadow = '';
        }
    });

    // Contador animado para números (se houver)
    const animateNumber = (element, target, duration = 2000) => {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start);
            }
        }, 16);
    };

    // Animar números das estatísticas quando aparecerem
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const number = entry.target.querySelector('.stat-number');
                if (number && !number.classList.contains('counted')) {
                    const target = parseInt(number.dataset.count || number.textContent);
                    number.classList.add('counted');
                    animateNumber(number, target);
                }
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-item').forEach(stat => {
        statsObserver.observe(stat);
    });
});





