import BaseEvent from './base.event';

export default class PurchaseEvent extends BaseEvent
{
    track() {
        if (!window.fbq) {
            return;
        }

        const urlParams = new URLSearchParams(window.location.search);

        if (!urlParams.has('orderId')) {
            return;
        }

        this.apiService.loadOrder(urlParams.get('orderId'), this._onLoadOrder.bind(this));
    }

    _onLoadOrder(order) {
        if (! Object.prototype.hasOwnProperty.call(order, 'contents')){
            return;
        }

        window.fbq('track', 'Purchase', {
            content_type: 'product',
            value: this.config.includeShippingCosts !== false ? order.totalPrice : order.positionPrice,
            currency: this.shop.currency,
            contents: order.contents,
            num_items: order.totalItems
        });
    }
}
