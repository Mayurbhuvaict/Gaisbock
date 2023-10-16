import BaseTrigger from './base.trigger';
import SearchEvent from '../events/search.event';
import DataService from '../services/data.service';

export default class SearchResultPageTrigger extends BaseTrigger
{
    supports(controller, action) {
        return controller === 'search' && action === 'search';
    }

    execute() {
        if (!this.active) {
            return;
        }

        const urlParams = new URLSearchParams(window.location.search);

        if (!urlParams.has('search')) {
            return;
        }

        const listing = new DataService().get('listing');

        this.initEvent(SearchEvent)
            .track(urlParams.get('search'), listing);
    }
}
