import BaseEvent from './base.event';

export default class CustomerHasAccountEvent extends BaseEvent
{
    track() {
        if (!window.fbq) {
            return;
        }

        window.fbq('trackCustom', 'CustomerHasAccount');
    }
}
