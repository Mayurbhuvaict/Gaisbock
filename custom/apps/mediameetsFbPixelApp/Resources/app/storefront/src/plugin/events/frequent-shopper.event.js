import BaseEvent from './base.event';

export default class FrequentShopperEvent extends BaseEvent
{
    track(customer) {
        if (!window.fbq) {
            return;
        }

        window.fbq('trackCustom', 'FrequentShopper', {
            total_orders: customer.orderCount
        });
    }
}
