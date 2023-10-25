Shopware.Component.override('sw-product-detail', {
    watch: {
        product() {
            this.initCustomFields();
        }
    },

    methods: {
        initCustomFields() {
            if (!this.product) {
                return;
            }

            if (!this.product.customFields) {
                this.product.customFields = {};
                this.$set(this.product, 'customFields', {
                    gaisbockProducts: null
                });
            }
        }
    }
});
