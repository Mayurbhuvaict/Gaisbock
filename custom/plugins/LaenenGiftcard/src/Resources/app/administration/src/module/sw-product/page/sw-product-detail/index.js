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
                    laeGiftcardEnabled: false,
                    laeGiftcardDesignType: 'simple',
                });
            }

            if (!this.product.customFields.laeGiftcardDesignType) {
                this.$set(this.product.customFields, 'laeGiftcardDesignType', 'simple');
            }
        }
    }
});
