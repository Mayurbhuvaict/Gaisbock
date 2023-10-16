import ListingHelper from '../helpers/listing.helper';
import BaseEvent from './base.event';

export default class ViewCategoryEvent extends BaseEvent
{
    track(listing) {
        if (!window.fbq) {
            return;
        }

        window.fbq('trackCustom', 'ViewCategory', {
            content_category: listing.path,
            category_name: listing.name,
            category_id: listing.id,
            content_type: 'product',
            contents: ListingHelper.getContents(listing),
            currency: this.shop.currency,
            value: 0
        });
    }
}
