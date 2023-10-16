Shopware.Component.override('sw-cms-list', {
    computed: {
        sortPageTypes() {
            let types = this.$super('sortPageTypes');

            types.push(
                { value: 'neti-store-locator', name: this.$t('neti-next-store-locator.sw-cms.sorting.labelSortByStoreLocator') },
            );

            return types;
        },

        pageTypes() {
            let types = this.$super('pageTypes');

            types['neti-store-locator'] = this.$t('neti-next-store-locator.sw-cms.sorting.labelSortByStoreLocator');

            return types;
        }
    }
});
