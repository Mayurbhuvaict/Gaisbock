import template from './template.twig';
import './cards/basic';
import './cards/misc';
import './cards/media';
import './cards/custom-fields';

Shopware.Component.register('neti-store-locator-detail-base', {
    template,

    props: {
        store: {
            type: Object,
            required: true
        },
        isCreateMode: {
            type: Boolean,
            required: true,
            default: false
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        },
        salesChannels: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        }
    }
});
