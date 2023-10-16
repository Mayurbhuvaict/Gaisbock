import BaseEvent from './base.event';

export default class PageviewEvent extends BaseEvent
{
    track() {
        if (!window.fbq) {
            return;
        }

        window.fbq('track', 'PageView');
    }
}
