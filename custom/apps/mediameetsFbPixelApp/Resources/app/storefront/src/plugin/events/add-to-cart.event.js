import BaseEvent from './base.event';

export default class AddToCartEvent extends BaseEvent
{
    track(lineItem, quantity) {
        if (!window.fbq) {
            return;
        }

        quantity = typeof quantity === 'undefined' ? 1 : quantity;
        const value = ((lineItem.price.totalPrice / lineItem.price.quantity) * quantity).toFixed(2);

        window.fbq('track', 'AddToCart', {
            content_type: 'product',
            content_name: lineItem.label,
            value: value,
            currency: this.shop.currency,
            contents: [{
                id: lineItem.payload.productNumber,
                quantity: quantity
            }]
        });
    }
}
