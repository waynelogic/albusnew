export class Modal {
    constructor(element, options = {})  {
        this.modal = null;
        if (element instanceof HTMLElement) {
            this.modal = element;
        }
        if (typeof element =="string" ) {
            this.modal = document.querySelector(element);
        }
        if (this.modal === null) {
            return console.log('Элемент не найден');
        }

        this.modalContent = this.modal.querySelector('.modal-content');
        const defOptions = {
            autoOpen: true,
            showClass: 'show',
            afterShown: () => {},
            afterClose: () => {
                this.destroy();
            },
            afterOpen: () => {},
            beforeClose: () => {}
        }
        this.options = {...defOptions, ...options};
        this.modal.addEventListener('click', ({target}) => {
            if (target.dataset.close != undefined) {
                this.toggle();
                this.options.afterClose();
            }
        })
        this.elements = {
            modal: this.modal,
            header: this.modal.querySelector('[data-modal-header]'),
            content: this.modal.querySelector('[data-modal-content]'),
            footer: this.modal.querySelector('[data-modal-footer]'),
        }
        if (this.options.autoOpen) {
            this.toggle();
        }
    }
    isShown() {
        return this.modal.classList.contains(this.options.showClass);
    }
    toggle() {
        const isShown = this.isShown();
        this.modal.classList.toggle(this.options.showClass, !isShown);
        if (!isShown) {
            this.options.afterShown({
                instance: this,
                elements: this.elements
            });
        }
    }
    destroy() {
        delete this;
    }
}


export default function init(element) {
    const modalId = element.dataset.modal;
    if (modalId === undefined) {
        return console.log('Вы не настроили кнопку модального окна');
    }
    element.addEventListener('click', () => {
        new Modal(modalId);
    })
}