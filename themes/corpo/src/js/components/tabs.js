class TabManager {
    constructor(element) {
        this.element = element;
        this.items = [];
        this.init();
    }
    init() {
        let buttons = this.element.querySelectorAll('[data-tab]');
        buttons.forEach(button => {
            let target = button.dataset.tab;
            this.items[target] = {
                button,
                content: document.querySelector(target),
                isOpen: this.isOpen(button)
            }
            button.addEventListener('click',() => this.openTab(target));
        });
    }
    isOpen(button) {
        return button.classList.contains('active') ? true : false;
    }
    openTab(target) {
        let item = this.items[target];
        if (item.isOpen) return;
        for (const [itemTarget, item] of Object.entries(this.items)) {
            item.button.classList.toggle('active', itemTarget === target);
            item.content.classList.toggle('open', itemTarget === target);
            item.isOpen = itemTarget === target;
        }
    }
}

export default function init($tabarea) {
    new TabManager($tabarea);
}