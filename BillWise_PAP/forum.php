<?php
/*
|--------------------------------------------------------------------------
| Página do fórum
|--------------------------------------------------------------------------
| Apresenta a listagem de tópicos, permite criar novos tópicos, responder,
| editar publicações próprias e apagar publicações do autor ou do admin.
*/

require_once 'php/base.php';

billwise_require_auth();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fórum - BillWise</title>
    <link rel="stylesheet" href="assets/css/estilo.css?v=3">
    <link rel="stylesheet" href="assets/css/barra_lateral.css?v=1">
    <link rel="stylesheet" href="assets/css/botoes.css?v=3">
    <link rel="stylesheet" href="assets/css/formularios.css?v=3">
    <link rel="stylesheet" href="assets/css/modais.css?v=3">
    <link rel="stylesheet" href="assets/css/painel.css?v=2">
</head>
<body>
    <div class="dashboard-layout">
        <?php include 'php/barra_lateral.php'; ?>
        <div class="main-content-wrapper">
            <main id="main-content" class="main">
                <div class="container">
                    <section class="dashboard-hero">
                        <div class="dashboard-hero-inner">
                            <div class="hero-greeting">
                                <h1>Fórum BillWise</h1>
                                <p>Partilhe ideias, dúvidas e estratégias financeiras com a comunidade.</p>
                            </div>
                            <div class="hero-actions">
                                <button class="btn btn-primary" id="novo-topico-btn" type="button">Novo Tópico</button>
                            </div>
                        </div>
                    </section>

                    <div id="formulario-topico-wrapper" class="card" style="display: none; margin-bottom: 2rem;">
                        <h2 id="titulo-formulario-topico">Novo Tópico</h2>
                        <form id="form-topico">
                            <input type="hidden" id="topico-id-edicao" value="">
                            <div class="form-group">
                                <label for="topico-titulo">Título</label>
                                <input type="text" id="topico-titulo" required minlength="5" placeholder="Ex: Como poupar para férias?">
                            </div>
                            <div class="form-group">
                                <label for="topico-conteudo">Conteúdo</label>
                                <textarea id="topico-conteudo" rows="5" required minlength="10" placeholder="Descreva a sua pergunta ou ideia."></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <button type="button" class="btn btn-secondary" id="cancelar-topico">Cancelar</button>
                            </div>
                        </form>
                    </div>

                    <section class="forum-topicos">
                        <h2>Tópicos Recentes</h2>
                        <div id="topicos-list" class="topicos-grid">
                            <div class="empty-state" style="display: none;">
                                <h3>Nenhum tópico ainda</h3>
                                <p>Seja o primeiro a partilhar uma ideia.</p>
                                <button class="btn btn-primary" id="empty-new-topico" type="button">Criar Primeiro Tópico</button>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div id="resposta-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="resposta-title">Responder</h2>
                <button class="close-btn" type="button">&times;</button>
            </div>
            <div class="modal-body">
                <form id="form-resposta">
                    <input type="hidden" id="resposta-topico-id">
                    <div class="form-group">
                        <label for="resposta-conteudo">Resposta</label>
                        <textarea id="resposta-conteudo" rows="4" required minlength="3" placeholder="Partilhe a sua resposta."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enviar Resposta</button>
                        <button type="button" class="btn btn-secondary modal-close">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/notificacoes.js?v=1"></script>
    <script>
    /*
    |--------------------------------------------------------------------------
    | Classe do fórum
    |--------------------------------------------------------------------------
    | Centraliza o carregamento, criação, edição, remoção e resposta aos
    | tópicos mostrados na página.
    */
    class Forum {
        constructor() {
            this.topicosList = document.getElementById('topicos-list');
            this.emptyState = this.topicosList.querySelector('.empty-state');
            this.formTopico = document.getElementById('form-topico');
            this.formResposta = document.getElementById('form-resposta');
            this.wrapperFormulario = document.getElementById('formulario-topico-wrapper');
            this.tituloFormulario = document.getElementById('titulo-formulario-topico');
            this.inputTopicoId = document.getElementById('topico-id-edicao');
            this.inputTitulo = document.getElementById('topico-titulo');
            this.inputConteudo = document.getElementById('topico-conteudo');
            this.respostaModal = document.getElementById('resposta-modal');
            this.initEvents();
            this.loadTopicos();
        }

        /*
        |--------------------------------------------------------------------------
        | Renderização dos cartões
        |--------------------------------------------------------------------------
        | Constrói o HTML do tópico e inclui botões condicionais consoante as
        | permissões devolvidas pelo servidor.
        */
        renderTopicoCard(topico) {
            const titulo = this.escapeHtml(topico.titulo);
            const conteudo = this.escapeHtml(topico.conteudo);
            const autor = this.escapeHtml(topico.autor_nome);
            const resumo = conteudo.length > 220 ? `${conteudo.slice(0, 220)}...` : conteudo;

            const acoes = [];

            if (topico.pode_editar) {
                acoes.push(`<button class="btn btn-outline editar-topico-btn" type="button" data-id="${topico.id}">Editar</button>`);
            }

            if (topico.pode_apagar) {
                acoes.push(`<button class="btn btn-secondary apagar-topico-btn" type="button" data-id="${topico.id}">Apagar</button>`);
            }

            acoes.push(`
                <button class="btn btn-outline responder-btn" type="button" data-id="${topico.id}" data-title="${titulo}">
                    Responder (${topico.respostas_count || 0})
                </button>
            `);

            return `
                <article class="topico-card card" data-topico-id="${topico.id}">
                    <div class="topico-header">
                        <h3>${titulo}</h3>
                        <span class="topico-meta">por <strong>${autor}</strong> · ${topico.criado_em}</span>
                    </div>
                    <p class="topico-conteudo">${resumo}</p>
                    <section class="topico-respostas">
                        <h4 class="topico-respostas-titulo">Respostas</h4>
                        <div class="topico-respostas-lista" data-respostas-topico="${topico.id}">
                            <p class="topico-respostas-vazio">A carregar respostas...</p>
                        </div>
                    </section>
                    <div class="topico-footer">
                        ${acoes.join('')}
                    </div>
                </article>
            `;
        }

        /*
        |--------------------------------------------------------------------------
        | Associação dos eventos dos cartões
        |--------------------------------------------------------------------------
        | Liga os botões dinâmicos às operações de responder, editar e apagar.
        */
        bindCardEvents(topicos = []) {
            const mapaTopicos = new Map(topicos.map((topico) => [String(topico.id), topico]));

            this.topicosList.querySelectorAll('.responder-btn').forEach((button) => {
                button.addEventListener('click', () => this.openResposta(button.dataset.id, button.dataset.title));
            });

            this.topicosList.querySelectorAll('.editar-topico-btn').forEach((button) => {
                button.addEventListener('click', () => {
                    const topico = mapaTopicos.get(button.dataset.id);
                    if (topico) {
                        this.abrirEdicao(topico);
                    }
                });
            });

            this.topicosList.querySelectorAll('.apagar-topico-btn').forEach((button) => {
                button.addEventListener('click', () => this.apagarTopico(button.dataset.id));
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Renderização das respostas
        |--------------------------------------------------------------------------
        | Mostra cada resposta por baixo do tópico correspondente e apresenta
        | um estado vazio quando ainda não existem participações.
        */
        renderRespostaCard(resposta) {
            const autor = this.escapeHtml(resposta.autor_nome);
            const conteudo = this.escapeHtml(resposta.conteudo).replace(/\n/g, '<br>');

            return `
                <article class="resposta-item">
                    <div class="resposta-meta">
                        <strong>${autor}</strong>
                        <span>${resposta.criado_em}</span>
                    </div>
                    <p class="resposta-conteudo">${conteudo}</p>
                </article>
            `;
        }

        /*
        |--------------------------------------------------------------------------
        | Carregamento das respostas
        |--------------------------------------------------------------------------
        | Obtém as respostas de um tópico específico e preenche a respetiva área
        | no cartão já renderizado.
        */
        async loadRespostas(topicoId) {
            const container = this.topicosList.querySelector(`[data-respostas-topico="${topicoId}"]`);

            if (!container) {
                return;
            }

            try {
                const response = await fetch(`php/obter_respostas.php?topico_id=${encodeURIComponent(topicoId)}`, {
                    credentials: 'same-origin',
                });
                const data = await response.json();

                if (!data.success || !Array.isArray(data.respostas) || data.respostas.length === 0) {
                    container.innerHTML = '<p class="topico-respostas-vazio">Ainda não existem respostas.</p>';
                    return;
                }

                container.innerHTML = data.respostas.map((resposta) => this.renderRespostaCard(resposta)).join('');
            } catch (error) {
                container.innerHTML = '<p class="topico-respostas-vazio">Não foi possível carregar as respostas.</p>';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Carregamento da listagem
        |--------------------------------------------------------------------------
        | Obtém os tópicos no servidor e atualiza a grelha principal.
        */
        async loadTopicos() {
            try {
                const response = await fetch('php/obter_topicos.php', { credentials: 'same-origin' });
                const data = await response.json();

                if (!data.success || !Array.isArray(data.topicos) || data.topicos.length === 0) {
                    this.topicosList.innerHTML = '';
                    this.topicosList.appendChild(this.emptyState);
                    this.emptyState.style.display = 'block';
                    return;
                }

                this.emptyState.style.display = 'none';
                this.topicosList.innerHTML = data.topicos.map((topico) => this.renderTopicoCard(topico)).join('');
                this.bindCardEvents(data.topicos);
                data.topicos.forEach((topico) => this.loadRespostas(topico.id));
            } catch (error) {
                Notifications.error('Não foi possível carregar os tópicos.', 'Erro');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Gestão do formulário principal
        |--------------------------------------------------------------------------
        | Alterna entre criação e edição sem sair da página.
        */
        abrirCriacao() {
            this.inputTopicoId.value = '';
            this.tituloFormulario.textContent = 'Novo Tópico';
            this.formTopico.reset();
            this.wrapperFormulario.style.display = 'block';
            this.inputTitulo.focus();
        }

        abrirEdicao(topico) {
            this.inputTopicoId.value = topico.id;
            this.tituloFormulario.textContent = 'Editar Tópico';
            this.inputTitulo.value = topico.titulo;
            this.inputConteudo.value = topico.conteudo;
            this.wrapperFormulario.style.display = 'block';
            this.wrapperFormulario.scrollIntoView({ behavior: 'smooth', block: 'center' });
            this.inputTitulo.focus();
        }

        fecharFormulario() {
            this.formTopico.reset();
            this.inputTopicoId.value = '';
            this.tituloFormulario.textContent = 'Novo Tópico';
            this.wrapperFormulario.style.display = 'none';
        }

        /*
        |--------------------------------------------------------------------------
        | Eventos principais da página
        |--------------------------------------------------------------------------
        | Regista os botões globais e as submissões dos formulários.
        */
        initEvents() {
            document.getElementById('novo-topico-btn').addEventListener('click', () => this.abrirCriacao());
            document.getElementById('cancelar-topico').addEventListener('click', () => this.fecharFormulario());
            document.getElementById('empty-new-topico').addEventListener('click', () => this.abrirCriacao());

            this.formTopico.addEventListener('submit', async (event) => {
                event.preventDefault();

                const topicoId = this.inputTopicoId.value;
                const emEdicao = topicoId !== '';
                const endpoint = emEdicao ? 'php/editar_topico.php' : 'php/adicionar_topico.php';
                const payload = {
                    titulo: this.inputTitulo.value,
                    conteudo: this.inputConteudo.value,
                };

                if (emEdicao) {
                    payload.topico_id = topicoId;
                }

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (!data.success) {
                    Notifications.error(data.error || 'Não foi possível guardar o tópico.', 'Erro');
                    return;
                }

                this.fecharFormulario();
                Notifications.success(emEdicao ? 'Tópico atualizado com sucesso.' : 'Tópico publicado com sucesso.', 'Fórum');
                this.loadTopicos();
            });

            this.formResposta.addEventListener('submit', async (event) => {
                event.preventDefault();

                const response = await fetch('php/adicionar_resposta.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        topico_id: document.getElementById('resposta-topico-id').value,
                        conteudo: document.getElementById('resposta-conteudo').value,
                    }),
                });

                const data = await response.json();

                if (!data.success) {
                    Notifications.error(data.error || 'Não foi possível enviar a resposta.', 'Erro');
                    return;
                }

                this.closeModal();
                Notifications.success('Resposta enviada.', 'Fórum');
                this.loadTopicos();
            });

            document.querySelector('#resposta-modal .close-btn').addEventListener('click', () => this.closeModal());
            document.querySelectorAll('.modal-close').forEach((button) => {
                button.addEventListener('click', () => this.closeModal());
            });

            this.respostaModal.addEventListener('click', (event) => {
                if (event.target === this.respostaModal) {
                    this.closeModal();
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Operações sobre cada tópico
        |--------------------------------------------------------------------------
        | Abre o modal de resposta e envia o pedido de remoção quando
        | autorizado.
        */
        openResposta(id, title) {
            document.getElementById('resposta-topico-id').value = id;
            document.getElementById('resposta-title').textContent = `Responder a: ${title}`;
            this.respostaModal.classList.add('active');
        }

        async apagarTopico(topicoId) {
            ConfirmModal.delete('Tem a certeza de que pretende apagar este tópico?', async () => {
                const response = await fetch('php/apagar_topico.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ topico_id: topicoId }),
                });

                const data = await response.json();

                if (!data.success) {
                    Notifications.error(data.error || 'Não foi possível apagar o tópico.', 'Erro');
                    return;
                }

                Notifications.success('Tópico apagado com sucesso.', 'Fórum');
                this.loadTopicos();
            });
        }

        closeModal() {
            this.respostaModal.classList.remove('active');
            this.formResposta.reset();
        }

        /*
        |--------------------------------------------------------------------------
        | Segurança do HTML apresentado
        |--------------------------------------------------------------------------
        | Escapa o texto vindo do servidor antes de o injetar na interface.
        */
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    new Forum();
    </script>
</body>
</html>


