import BaseTrigger from './base.trigger';
import ViewCategoryEvent from '../events/view-category.event';
import DataService from '../services/data.service';

export default class ListingViewTrigger extends BaseTrigger
{
    supports(controller, action) {
        return controller === 'navigation' && (action === 'home' || action === 'index');
    }

    execute() {
        if (!this.active) {
            return;
        }

        const listing = new DataService().get('listing');

        if (listing === null) {
            this.warn('Listing data not found.');
            return;
        }

        this.initEvent(ViewCategoryEvent)
            .track(listing);
    }
}
