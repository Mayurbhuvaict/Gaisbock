import BaseTrigger from './base.trigger';
import InitiateCheckoutEvent from '../events/initiate-checkout.event';
import PageviewEvent from '../events/pageview.event';
import Storage from 'src/helper/storage/storage.helper';

export default class PageviewTrigger extends BaseTrigger
{
    supports() {
        return true;
    }

    execute() {
        if (!this.active) {
            return;
        }

        this._handlePageViewEvent();
        this._handleInitiateCheckoutEvent();
    }

    _handlePageViewEvent() {
        this.initEvent(PageviewEvent)
            .track();
    }

    _handleInitiateCheckoutEvent() {
        const storageKey = this.getStorageKey('initiateCheckout');
        const initiateCheckoutStatus = Storage.getItem(storageKey);

        if (parseInt(initiateCheckoutStatus, 10) === 1) {
            Storage.removeItem(storageKey);

            this.initEvent(InitiateCheckoutEvent)
                .track();
        }
    }
}
