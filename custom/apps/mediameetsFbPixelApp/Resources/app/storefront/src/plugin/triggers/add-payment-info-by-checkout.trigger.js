import DomAccessHelper from 'src/helper/dom-access.helper';
import BaseTrigger from './base.trigger';
import Storage from 'src/helper/storage/storage.helper';
import AddPaymentInfoEvent from '../events/add-payment-info.event';

export default class AddPaymentInfoByCheckoutTrigger extends BaseTrigger
{
    supports(controllerName, actionName) {
        return controllerName === 'checkout' && actionName === 'confirmpage';
    }

    execute() {
        this._handleAddedPaymentInfo();

        const paymentForm = DomAccessHelper.querySelector(document, '#changePaymentForm', false);

        if (!paymentForm) {
            return;
        }

        paymentForm.addEventListener('change', this._onChangePaymentForm.bind(this));
    }

    _onChangePaymentForm() {
        if (!this.active) {
            return;
        }

        Storage.setItem(this.getStorageKey('addedPaymentInfo'), 1);
    }

    _handleAddedPaymentInfo() {
        const storageKey = this.getStorageKey('addedPaymentInfo');
        const addedPaymentInfo = Storage.getItem(storageKey);

        if (parseInt(addedPaymentInfo, 10) === 1) {
            Storage.removeItem(storageKey);

            this.initEvent(AddPaymentInfoEvent)
                .track();
        }
    }
}
