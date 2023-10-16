import template from './template.twig';

Shopware.Component.register('neti-store-locator-general-misc-card', {
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
