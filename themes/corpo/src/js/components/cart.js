export default  function initCart($element) {
    const $root = $element;

    // Добавляем прослушиватели
    function eventHandlers() {
        console.log(333);
        let $removeItemButtons = find('.remove-item', true);
        $removeItemButtons.forEach(button => {
            button.addEventListener('click', () => onRemoveItemFromTable(button) );
        });
    
        let $clearCartButton = find('.remove-all');
        $clearCartButton.addEventListener('click', ()=> clearCart());

        let $quantityInputs = find('.cart-counter .val', true);
        $quantityInputs.forEach(input => {
            input.onchange = (input) => { onChangeItem(input.target) };
        });
    }
    eventHandlers();

    // Найти элемент относительно ROOT
    function find($sSelector, all = false) {
        if (all == true) {
            return $root.querySelectorAll($sSelector);
        } else {
            return $root.querySelector($sSelector);
        }
    }

    function getOfferId($element) {
        let $row = $element.closest('tr');
        return $row.dataset.id;
    }
    
    // Посчитать 
    function countItems() {
        let $cartItems = find('tbody tr', true);
        return $cartItems.length;
    }

    function onChangeItem($input) {
        let $value = parseInt($input.value);     
        let $offer_id = getOfferId($input);
        let $data = {
            'cart': [
                {'offer_id': $offer_id, 'quantity': $value }
            ],
        };
        sendRequestUpdateItem($data);
    }

    function onRemoveItemFromTable($btnRemoveItem) {
        if (countItems() > 1) {
            let $row = $btnRemoveItem.closest('tr');
            let $offer_id = $row.dataset.id;
            sendRequestRemoveItem($offer_id);
            $row.remove();
        } else {
            clearCart();
        }
    }

    function clearCart() {
        oc.ajax('Cart::onClear', {
            update: { 
                'cart/data': '#cart-data',
                'common/header/cart-button': '#cart-button'
            },
            afterUpdate: function() {
                oc.flashMsg({message: 'Корзина была очищена!', type: 'success', interval: 1});
            }            
        })
    }

    function sendRequestRemoveItem($offer_id) {
        var data = {
            'cart': [$offer_id]
        };
        oc.ajax('Cart::onRemove', {
            'data': data,
            update: { 
                'cart/footer': '#cart-footer',
                'common/header/cart-button': '#cart-button'
            },
            afterUpdate: function() {
                oc.flashMsg({message: 'Товар был удален из корзины!', type: 'success', interval: 1});
            }            
        })
    }
    function sendRequestUpdateItem($data) {
        oc.ajax('Cart::onUpdate', {
            'data': $data,
            update: { 'cart/cart-list': '#cart-list' },
            afterUpdate: function() {
                oc.flashMsg({message: 'Количество товара было изменено!', type: 'success', interval: 1});
                eventHandlers();
            }            
        })
    }
}