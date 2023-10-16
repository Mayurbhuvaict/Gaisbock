import template from './template.twig';
import './cards/basic';

Shopware.Component.register('neti-store-locator-detail-contact', {
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
        }
    }
});
