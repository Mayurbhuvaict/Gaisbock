import BaseTriggerEventAware from './base.trigger.event-aware';
import Storage from 'src/helper/storage/storage.helper';
import DomAccessHelper from 'src/helper/dom-access.helper';

export default class InitiateCheckoutByOffcanvasTrigger extends BaseTriggerEventAware
{
    supports() {
        return true;
    }

    events () {
        return {
            OffCanvasCart: {
                offCanvasOpened: this._offCanvasOpened.bind(this)
            }
        };
    }

    _offCanvasOpened() {
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
