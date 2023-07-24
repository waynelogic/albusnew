class Accordion {
    constructor(element) {
        this.element = element;
        this.items = [];
        this.init();
        this.element.addEventListener('click', ({target}) => {
            if (target.dataset.action === 'toggle') {
                this.toggle(target.dataset.target);
            }
            if (target.dataset.action === 'collapse') {
                this.collapse(target.dataset.target);
            }
        })
    }
    init() {
        let buttons = this.element.querySelectorAll('.accordion-button');
        buttons.forEach(button => {
            let target = button.dataset.target;
            this.items[target] = {
                button,
                content: document.querySelector(target),
                isOpen: false
            }
        });
    }
    toggleItem(item, open) {
        if (open === true){  
            item.content.classList.add('open');
            item.button.classList.add('active');
            item.content.style.maxHeight = item.content.scrollHeight + 'px';
            item.isOpen = true;
        } else {
            item.content.classList.remove('open');
            item.button.classList.remove('active');
            item.content.style.maxHeight = null;
            item.isOpen = false;
        }
    }
    toggle(target) {
        for (const [itemTarget, item] of Object.entries(this.items)) {
            if (itemTarget === target) {
                this.toggleItem(item, !item.isOpen)
            } else {
                this.toggleItem(item, false);
            }
        }
    }
    collapse(target) {
        this.toggleItem(this.items[target], !this.items[target].isOpen);
    }
}

export default function init($element) {
    new Accordion($element);
}