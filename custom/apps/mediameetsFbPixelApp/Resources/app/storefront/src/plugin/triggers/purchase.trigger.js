import BaseTrigger from './base.trigger';
import PurchaseEvent from '../events/purchase.event';

export default class PurchaseTrigger extends BaseTrigger
{
    supports(controller, action) {
        return controller === 'checkout' && action === 'finishpage';
    }

    execute() {
        if (!this.active) {
            return;
        }

        this.initEvent(PurchaseEvent)
            .track();
    }
}
