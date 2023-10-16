import BaseTrigger from './base.trigger';
import DomAccessHelper from 'src/helper/dom-access.helper';
import Storage from 'src/helper/storage/storage.helper';
import CartAndOrderHelper from '../helpers/cart-and-order.helper';
import AddToCartEvent from '../events/add-to-cart.event';

export default class AddToCartByNumberTrigger extends BaseTrigger
{
    storageKey = this.getStorageKey('productAddedToCartByNumber');

    supports(controllerName, actionName) {
        return controllerName === 'checkout' && actionName === 'cartpage';
    }

    execute() {
        if (!this.active) {
            return;
        }

        const addedProduct = Storage.getItem(this.storageKey);

        if (typeof addedProduct === 'string') {
            this._trackAddedProduct(addedProduct);
        }

        Storage.removeItem(this.storageKey);

        const addToCartForm = DomAccessHelper.querySelector(document, '.cart-add-product', false);
        if (!addToCartForm) {
            return;
        }

        addToCartForm.addEventListener('submit', this._storeProductNumber.bind(this));
    }

    _storeProductNumber(event) {
        const input = DomAccessHelper.querySelector(event.currentTarget, '#addProductInput');
        Storage.setItem(this.storageKey, input.value);
    }

    _trackAddedProduct(productNumber) {
        const me = this;

        this.apiService.loadCart(function(cart) {
            const lineItem = CartAndOrderHelper.getProductByNumber(cart, productNumber);

            if (lineItem === null) {
                me.warn('Product not found in cart.');
                return;
            }

            me.initEvent(AddToCartEvent)
                .track(lineItem, 1);
        });
    }
}
