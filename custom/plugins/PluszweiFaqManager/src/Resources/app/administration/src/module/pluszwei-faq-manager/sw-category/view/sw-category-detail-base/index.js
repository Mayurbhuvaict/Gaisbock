const { Component } = Shopware;

Component.override('sw-category-detail-base', {
    computed: {
        categoryTypes() {
            const types = this.$super('categoryTypes');
            types.push({
                value: "faq",
                label: this.$tc("sw-category.base.general.types.faq")
            });

            return types;
        }
    }
});
