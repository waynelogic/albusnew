const CLASS_NAME_SHOW = 'show';

class Dropdown {
    constructor(element) {
        this.element = element;
        this.aim = this.findAim(element.dataset.dropdown);
        this.clickOutside = this.clickOutside.bind(this);
        element.addEventListener("click", () => this.toggle());
    }
    findAim($id) {
        return document.getElementById($id);
    }
    toggle() {
        return this._isShown() ? this.hide() : this.show();
    }
    hide() {
        this.aim.classList.remove(CLASS_NAME_SHOW);
    }
    show() {
        this.aim.classList.add(CLASS_NAME_SHOW);
        window.addEventListener('click', this.clickOutside.bind(this));
    }
    clickOutside(e) {
        if (!this.element.contains(e.target)) {
            this.hide();
            window.removeEventListener('click', this.clickOutside);
        }    
    }
    _isShown() {
        return this.aim.classList.contains(CLASS_NAME_SHOW);
    }
}

export default function initDropdown() {
    let $dropDownButtons = document.querySelectorAll('[data-dropdown]');
    $dropDownButtons.forEach(element => {
        new Dropdown(element);
    });
}