import { initFormTriggers } from "./form-triggers";

class Calculator {
    constructor($element) {
        this.calcArea = $element;
        this.$totalPrice = this.calcArea.querySelector('.total-price');
        this.basePrice = parseInt(this.calcArea.dataset.calcBaseprice);
        this.totalPrice = 0;
        this.debounce = this.debounce(this.reload,1000);
        this.init();
    }
    debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };
    init() {
        initFormTriggers(this.calcArea);
        this.items = this.calcArea.querySelectorAll('.calc-item');
        this.items.forEach(item => {
            item.addEventListener('change', () => {
                this.debounce()
            });
        });
        this.count();
    }
    count() {
        let totalPrice = this.basePrice;
        this.items.forEach(item => {
            if (item.checked) {
                totalPrice += parseInt(item.value);
            }
        });
        this.totalPrice = Intl.NumberFormat('ru-RU').format(totalPrice)
        this.$totalPrice.innerHTML = this.totalPrice;
    }
    reload() {
        this.count();
        this.flash('Стоимость заказа: ' + this.totalPrice + ' руб.');
    }
    flash(text, css = 'info', interval = 3 ) {
        oc.flashMsg({
            'text': text,
            'class': css,
            'interval': 3
        })
    }
}

export default function init($element) {
    new Calculator($element);
}