const CLASS_NAME_SHOW = 'show';

class Modal {
    constructor(button) {
        this.button = button;
        this.findElements(button.dataset.modal);
        button.addEventListener("click", () => this.toggle());
    }
    findElements($id) {
        this.aim = document.getElementById($id);
        this.content = this.aim.querySelector('.modal-content');
    }
    toggle() {
        return this._isShown() ? this.hide() : this.show();
    }
    hide() {
        this.aim.classList.remove(CLASS_NAME_SHOW);
    }
    show() {
        this.aim.classList.add(CLASS_NAME_SHOW);
    }
    _isShown() {
        return this.aim.classList.contains(CLASS_NAME_SHOW);
    }
}

export default function initModal() {
    let $modalButtons = document.querySelectorAll('[data-modal]');
    $modalButtons.forEach(element => {
        new Modal(element);
    });
}