// Sistema de Notificações Toast Modernas - exibe mensagens temporárias ao utilizador
const Notifications = {
    container: null,
    
    // Inicializar container de toasts (cria elemento no DOM)
    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },
    
    // Mostrar notificação: message (texto), type (success/error/warning/info), title (opcional), duration (ms)
    show(message, type = 'info', title = null, duration = 4000) {
        this.init();
        
        // Ícones: removidos para versão sem emojis; estilos por tipo via CSS
        const icons = {
            success: '',
            error: '',
            warning: '',
            info: ''
        };
        
        // Títulos padrão para cada tipo
        const titles = {
            success: title || 'Sucesso!',
            error: title || 'Erro!',
            warning: title || 'Atenção!',
            info: title || 'Informação'
        };
        
        // Criar elemento toast com estrutura HTML
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">
                <div class="toast-title">${titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;
        
        this.container.appendChild(toast);
        
        // Auto-remover após duração especificada (com animação de saída)
        if (duration > 0) {
            setTimeout(() => {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    },
    
    success(message, title = null) {
        this.show(message, 'success', title);
    },
    
    error(message, title = null) {
        this.show(message, 'error', title);
    },
    
    warning(message, title = null) {
        this.show(message, 'warning', title);
    },
    
    info(message, title = null) {
        this.show(message, 'info', title);
    }
};

// Modal de Confirmação Personalizada
const ConfirmModal = {
    modal: null,
    
    init() {
        if (!this.modal) {
            this.modal = document.createElement('div');
            this.modal.className = 'confirm-modal';
            this.modal.innerHTML = `
                <div class="confirm-modal-content">
                    <div class="confirm-modal-icon"></div>
                    <h3 class="confirm-modal-title"></h3>
                    <p class="confirm-modal-message"></p>
                    <div class="confirm-modal-buttons">
                        <button class="confirm-modal-cancel">Cancelar</button>
                        <button class="confirm-modal-confirm">Confirmar</button>
                    </div>
                </div>
            `;
            document.body.appendChild(this.modal);
        }
    },
    
    show(options = {}) {
        this.init();
        
        const {
            title = 'Confirmar ação',
            message = 'Tem certeza que deseja continuar?',
            confirmText = 'Confirmar',
            cancelText = 'Cancelar',
            icon = '',
            iconClass = 'warning',
            confirmClass = '',
            onConfirm = () => {},
            onCancel = () => {}
        } = options;
        
        const iconElement = this.modal.querySelector('.confirm-modal-icon');
        iconElement.className = `confirm-modal-icon ${iconClass}`;
        iconElement.textContent = icon;
        
        this.modal.querySelector('.confirm-modal-title').textContent = title;
        this.modal.querySelector('.confirm-modal-message').textContent = message;
        
        const confirmBtn = this.modal.querySelector('.confirm-modal-confirm');
        const cancelBtn = this.modal.querySelector('.confirm-modal-cancel');
        
        confirmBtn.textContent = confirmText;
        cancelBtn.textContent = cancelText;
        
        if (confirmClass) {
            confirmBtn.className = `confirm-modal-confirm ${confirmClass}`;
        } else {
            confirmBtn.className = 'confirm-modal-confirm';
        }
        
        // Remover listeners antigos
        const newConfirmBtn = confirmBtn.cloneNode(true);
        const newCancelBtn = cancelBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
        
        // Adicionar novos listeners
        newConfirmBtn.addEventListener('click', () => {
            this.hide();
            onConfirm();
        });
        
        newCancelBtn.addEventListener('click', () => {
            this.hide();
            onCancel();
        });
        
        // Fechar ao clicar fora
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
                onCancel();
            }
        });
        
        this.modal.classList.add('active');
    },
    
    hide() {
        if (this.modal) {
            this.modal.classList.remove('active');
        }
    },
    
    // Atalhos para tipos comuns
    confirm(message, onConfirm, onCancel = () => {}) {
        this.show({
            title: 'Confirmação',
            message,
            icon: '',
            iconClass: 'info',
            confirmClass: 'primary',
            confirmText: 'Confirmar',
            onConfirm,
            onCancel
        });
    },
    
    delete(message, onConfirm, onCancel = () => {}) {
        this.show({
            title: 'Eliminar',
            message,
            icon: '',
            iconClass: 'danger',
            confirmText: 'Eliminar',
            onConfirm,
            onCancel
        });
    },
    
    warning(message, onConfirm, onCancel = () => {}) {
        this.show({
            title: 'Atenção',
            message,
            icon: '',
            iconClass: 'warning',
            confirmText: 'Continuar',
            onConfirm,
            onCancel
        });
    }
};

// Substituir alert nativo por toast
window.showAlert = function(message, type = 'info') {
    Notifications.show(message, type);
};

// Substituir confirm nativo
window.showConfirm = function(message, callback) {
    ConfirmModal.confirm(message, callback);
};


