import DomAccessHelper from 'src/helper/dom-access.helper';
import BaseTrigger from './base.trigger';
import Storage from 'src/helper/storage/storage.helper';

export default class InitiateCheckoutByCartTrigger extends BaseTrigger
{
    supports(controllerName, actionName) {
        return controllerName === 'checkout' && actionName === 'cartpage';
    }

    execute() {
        const beginCheckoutBtn = DomAccessHelper.querySelector(document, '.begin-checkout-btn', false);

        if (!beginCheckoutBtn) {
            return;
        }

        beginCheckoutBtn.addEventListener('click', this._onBeginCheckout.bind(this));
    }

    _onBeginCheckout() {
        if (!this.active) {
            return;
        }

        Storage.setItem(this.getStorageKey('initiateCheckout'), 1);
    }
}
