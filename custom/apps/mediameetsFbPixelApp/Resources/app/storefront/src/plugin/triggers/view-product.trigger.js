import BaseTrigger from './base.trigger';
import Storage from 'src/helper/storage/storage.helper';
import ViewContentEvent from '../events/view-content.event';
import CustomizeProductEvent from '../events/customize-product.event';
import DataService from '../services/data.service';

export default class ViewProductTrigger extends BaseTrigger
{
    supports(controllerName, actionName) {
        return controllerName === 'product' && actionName === 'index';
    }

    execute() {
        if (!this.active) {
            return;
        }

        const product = new DataService().get('product');

        if (product === null) {
            this.warn('Product data not found.');
            return;
        }

        this.initEvent(ViewContentEvent)
            .track(product);

        this._handleCustomizeProductEvent(product);
    }

    _handleCustomizeProductEvent(product)
    {
        const storageKey = this.getStorageKey('customizedProduct');
        const customizedProduct = Storage.getItem(storageKey);

        if (parseInt(customizedProduct, 10) === 1) {
            Storage.removeItem(storageKey);

            this.initEvent(CustomizeProductEvent)
                .track(product);
        }
    }
}
