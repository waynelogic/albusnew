class CartCounter {
    constructor(element) {
        this.element = element;
        this.findObjects();
    }
    findObjects() {
        this.minusBtn = this.element.querySelector('[minus]');
        this.plusBtn = this.element.querySelector('[plus]');
        this.input = this.element.querySelector('input');
        this.input.addEventListener("change", () => this.checkValue() );
        this.minusBtn.addEventListener('click', () => this.minus() );
        this.plusBtn.addEventListener('click', () => this.plus() );
    }
    minus() {
        if (this.currentVal() > 1) {
            this.setVal(this.currentVal() - 1)
            this.makeEvent();
        }
    }
    plus() {
        this.setVal(this.currentVal() + 1)
        this.makeEvent();
    }
    currentVal() {
        return parseInt(this.input.value);
    }
    setVal($value) {
        this.input.value = $value;
        this.input.setAttribute('value', $value);
    }
    checkValue() {
        if(Number.isNaN(Number(this.input.value)) === true) {
            this.setVal(1);
        };
    }
    makeEvent() {
        let evt = document.createEvent("HTMLEvents");
        evt.initEvent("change", false, true);
        this.input.dispatchEvent(evt);
    }
}

export default function initCounter($element) {
    new CartCounter($element);
}