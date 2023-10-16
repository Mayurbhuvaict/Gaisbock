import template from './sw-product-detail-base.html.twig';

const { Mixin } = Shopware;
const { Component } = Shopware;

Component.override('sw-product-detail-base', {
    template,

    data() {
        return {
            acris_product_customer_group_exclude_sitemap: false
        };
    },

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    computed: {
        hasParent() {
            return !!this.parentProduct.id;
        },

        customerGroupRepository() {
            return this.repositoryFactory.create(this.product.extensions.acrisBlockCustomerGroup.entity);
        }
    },

    updated() {
        this.refreshSitemapCheckboxValue();
    },

    methods: {
        createdComponent() {
            this.$super('createdComponent');
            this.refreshSitemapCheckboxValue();
        },

        refreshSitemapCheckboxValue() {
            if(this.product.customFields == null) {
                this.product.customFields = {acris_product_customer_group_exclude_sitemap: null};
            }
            if(this.parentProduct.customFields == null) {
                this.parentProduct.customFields = {acris_product_customer_group_exclude_sitemap: null};
            }
        },

        sitemapExcludeRemoveInheritanceFunction() {
            this.product.customFields.acris_product_customer_group_exclude_sitemap = this.parentProduct.customfields ? this.parentProduct.customFields.acris_product_customer_group_exclude_sitemap : false;
            this.$refs.sitemapExcludeInheritation.forceInheritanceRemove = true;

            return this.product.customFields.acris_product_customer_group_exclude_sitemap;
        },

        sitemapExcludeRestoreInheritanceFunction() {
            this.$refs.sitemapExcludeInheritation.forceInheritanceRemove = false;
            this.product.customFields.acris_product_customer_group_exclude_sitemap = null;

            return this.product.customFields.acris_product_customer_group_exclude_sitemap;
        }
    }
});
