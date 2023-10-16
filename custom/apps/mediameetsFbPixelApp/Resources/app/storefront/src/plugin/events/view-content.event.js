import BaseEvent from './base.event';

export default class ViewContentEvent extends BaseEvent
{
    track(product) {
        if (!window.fbq) {
            return;
        }

        const eventData = {
            content_category: product.category,
            content_type: 'product',
            content_name: product.name,
            contents: [{
                id: product.productNumber,
                quantity: 1
            }],
            currency: this.shop.currency,
            value: product.price
        };

        window.fbq('track', 'ViewContent', eventData);
        window.fbq('trackCustom', 'ViewProduct', eventData);
    }
}
