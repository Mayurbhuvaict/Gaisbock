import BaseEvent from './base.event';

export default class CustomizeProductEvent extends BaseEvent
{
    track(product) {
        if (!window.fbq) {
            return;
        }

        window.fbq('track', 'CustomizeProduct', {
            content_category: product.category,
            content_type: 'product',
            content_name: product.name,
            contents: [{
                id: product.productNumber,
                quantity: 1
            }],
            currency: this.shop.currency,
            value: product.price
        });
    }
}
