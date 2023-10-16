import template from './template.twig';
import './cards/basic';
import './cards/content';
import './cards/seo';
import './cards/cms';

Shopware.Component.register('neti-store-locator-detail-details', {
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
