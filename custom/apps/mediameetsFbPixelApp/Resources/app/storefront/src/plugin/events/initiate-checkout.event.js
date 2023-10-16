import BaseEvent from './base.event';
import CartAndOrderHelper from '../helpers/cart-and-order.helper';

export default class InitiateCheckoutEvent extends BaseEvent
{
    track() {
        if (!window.fbq) {
            return;
        }

        this.apiService.loadCart(this._onCartLoaded.bind(this));
    }

    _onCartLoaded(cart) {
        if (cart === null) {
            return;
        }

        window.fbq('track', 'InitiateCheckout', {
            content_type: 'product',
            value: cart.price.totalPrice,
            currency: this.shop.currency,
            contents: CartAndOrderHelper.getContents(cart),
            num_items: CartAndOrderHelper.getTotalItems(cart)
        });
    }
}
