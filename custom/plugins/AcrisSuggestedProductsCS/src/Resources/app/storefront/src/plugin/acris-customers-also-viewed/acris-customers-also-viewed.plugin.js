import Plugin from 'src/plugin-system/plugin.class';
import CookieStorage from 'src/helper/storage/cookie-storage.helper';

export default class AcrisCustomersAlsoViewedPlugin extends Plugin {

    static options = {
        id: '',
        cookieLimit: 5
    }

    init() {
        const acrisCustomersAlsoViewedCookie = CookieStorage.getItem('acris_customers_also_viewed');

        if (!!acrisCustomersAlsoViewedCookie) {
            CookieStorage.setItem('acris_customers_also_viewed', this.options.id, 30);
        }
    }
}
