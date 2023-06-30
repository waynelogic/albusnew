class BuyForm {
    constructor(form) {
        this.form = form;
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
    }
    handleSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        let data = {
            'cart': [
                {'offer_id': formData.get('offer_id'), 'quantity': parseInt(formData.get('quantity'))}
            ],
            'shipping_type_id': 1,
            'payment_method_id': 2
        };
        oc.ajax('Cart::onAdd', {
            'data': data,
            update: { 'common/header/cart-button': '#cart-button' },
            afterUpdate: function() {
                oc.flashMsg({message: 'Товар был добавлен в корзину!', type: 'success', interval: 1});
            }            
        })
    }
}

export default function initShop($form) {
    new BuyForm($form);
}