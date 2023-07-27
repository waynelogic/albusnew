class Calculator {
    constructor($element) {
        this.calcArea = $element;
        this.$totalPrice = this.calcArea.querySelector('.total-price');
        this.totalPrice = 0;
        this.init();
        this.count();
    }
    init() {
        this.basePrice = parseInt(this.calcArea.dataset.calcBaseprice);
        this.items = this.calcArea.querySelectorAll('.calc-item');
        this.items.forEach(item => {
            item.addEventListener('change', () => {
                this.count();
                this.flash('Стоимость заказа: ' + this.totalPrice + ' руб.');

            });
        });
    }
    count() {
        let totalPrice = this.basePrice;
        this.items.forEach(item => {
            if (item.checked) {
                console.log(item);
                totalPrice += parseInt(item.value);
            }
        });
        this.totalPrice = Intl.NumberFormat('ru-RU').format(totalPrice)
        this.$totalPrice.innerHTML = this.totalPrice;
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