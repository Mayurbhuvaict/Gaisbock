import ListingHelper from '../helpers/listing.helper';
import BaseEvent from './base.event';

export default class SearchEvent extends BaseEvent
{
    track(searchTerm, listing) {
        if (!window.fbq) {
            return;
        }

        window.fbq('track', 'Search', {
            search_string: searchTerm,
            content_type: 'product',
            contents: ListingHelper.getContents(listing),
            currency: this.shop.currency,
            value: 0
        });
    }
}
