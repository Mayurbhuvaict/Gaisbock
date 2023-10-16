import BaseTriggerEventAware from './base.trigger.event-aware';
import CartAndOrderHelper from '../helpers/cart-and-order.helper';
import AddToCartEvent from '../events/add-to-cart.event';

export default class AddToCartByButtonTrigger extends BaseTriggerEventAware
{
    added = {
        id: null,
        quantity: null
    };

    supports() {
        return true;
    }

    events () {
        return {
            AddToCart: {
                beforeFormSubmit: this._cacheAddedProduct.bind(this)
            },
            OffCanvasCart: {
                offCanvasOpened: this._trackAddedProduct.bind(this)
            }
        };
    }

    _cacheAddedProduct(event) {
        if (!this.active) {
            return;
        }

        const me = this;

        event.detail.forEach((value, key) => {
            if (key.endsWith('[id]')) {
                me.added.id = value;
            }
            if (key.endsWith('[quantity]')) {
                me.added.quantity = value;
            }
        });
    }

    _trackAddedProduct() {
        if (!this.active || this.added.id === null) {
            return;
        }

        const quantity = this.added.quantity === null ? 1 : this.added.quantity;

        const me = this;

        this.apiService.loadCart(function(cart){
            const lineItem = CartAndOrderHelper.getProductById(cart, me.added.id);

            me._reset();

            if (lineItem === null) {
                me.warn('Product not found in cart.');
                return;
            }

            me.initEvent(AddToCartEvent)
                .track(lineItem, quantity);
        });
    }

    _reset() {
        this.added.id = null;
        this.added.quantity = null;
    }
}
