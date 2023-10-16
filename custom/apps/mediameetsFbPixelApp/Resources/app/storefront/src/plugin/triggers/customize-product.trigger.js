import BaseTriggerEventAware from './base.trigger.event-aware';
import Storage from 'src/helper/storage/storage.helper';

export default class CustomizeProductTrigger extends BaseTriggerEventAware
{
    supports() {
        return true;
    }

    events () {
        return {
            VariantSwitch: {
                onChange: this._storeCustomization.bind(this)
            }
        };
    }

    _storeCustomization() {
        if (!this.active) {
            return;
        }

        Storage.setItem(this.getStorageKey('customizedProduct'), 1);
    }
}
