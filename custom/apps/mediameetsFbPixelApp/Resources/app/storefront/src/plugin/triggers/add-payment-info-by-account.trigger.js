import BaseTrigger from './base.trigger';
import AddPaymentInfoEvent from '../events/add-payment-info.event';

export default class AddPaymentInfoByAccountTrigger extends BaseTrigger
{
    supports(controllerName, actionName) {
        return controllerName === 'accountpayment' && actionName === 'paymentoverview';
    }

    execute() {
        if (!this.active) {
            return;
        }

        this.initEvent(AddPaymentInfoEvent)
            .track();
    }
}
