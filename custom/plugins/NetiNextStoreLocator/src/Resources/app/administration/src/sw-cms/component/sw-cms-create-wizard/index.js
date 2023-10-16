import template from './sw-cms-create-wizard.html.twig';

Shopware.Component.override('sw-cms-create-wizard', {
    template,
    computed: {
        pagePreviewStyle() {
            if ('neti_store_locator' === this.page.type) {
                return {};
            }

            return this.$super('pagePreviewStyle');
        }
    },
    data() {
        return {
            pageTypeNames: {
                neti_store_locator: this.$t('neti-next-store-locator.sw-cms.sorting.labelSortByStoreLocator')
            },
            pageTypeIcons: {
                neti_store_locator: 'regular-products'
            },
        };
    },
});
