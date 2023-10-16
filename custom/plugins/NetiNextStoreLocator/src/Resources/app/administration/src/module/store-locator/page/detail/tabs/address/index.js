import template from './template.twig';
import './cards/basic';
import './cards/misc';
import './cards/google_places';

Shopware.Component.register('neti-store-locator-detail-address', {
    template,

    props: {
        store: {
            type: Object,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        },
        countries: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        }
    }
});
