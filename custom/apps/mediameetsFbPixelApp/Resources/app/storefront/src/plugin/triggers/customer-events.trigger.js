import BaseTrigger from './base.trigger';
import CustomerHasAccountEvent from '../events/customer-has-account.event';
import FrequentShopperEvent from '../events/frequent-shopper.event';

export default class CustomerEventsTrigger extends BaseTrigger
{
    supports(controllerName) {
        const supportedControllers = [
            'accountprofile',
            'address',
            'accountpayment',
            'accountorder'
        ];

        return supportedControllers.includes(controllerName);
    }

    execute() {
        if (!this.active) {
            return;
        }

        this.apiService.loadContext(this._onContextLoaded.bind(this));
    }

    _onContextLoaded(context) {
        if (! Object.prototype.hasOwnProperty.call(context, 'customer')) {
            return;
        }

        if (context.customer.guest === false) {
            this.initEvent(CustomerHasAccountEvent)
                .track();
        }

        if (context.customer.orderCount >= 2) {
            this.initEvent(FrequentShopperEvent)
                .track(context.customer);
        }
    }
}
