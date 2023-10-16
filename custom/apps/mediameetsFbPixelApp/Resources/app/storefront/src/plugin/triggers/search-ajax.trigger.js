import BaseTriggerEventAware from './base.trigger.event-aware';
import SearchEvent from '../events/search.event';

export default class SearchAjaxTrigger extends BaseTriggerEventAware
{
    supports() {
        return true;
    }

    events () {
        return {
            SearchWidget: {
                handleInputEvent: this._trackEvent.bind(this)
            }
        };
    }

    _trackEvent(event) {
        if (!this.active) {
            return;
        }

        this.initEvent(SearchEvent)
            .track(event.detail.value);
    }
}
