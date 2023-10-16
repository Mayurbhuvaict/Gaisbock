import template from './sw-category-detail-base.html.twig';
const { mapState } = Shopware.Component.getComponentHelper();
const { Mixin } = Shopware;
const { Component } = Shopware;

Component.override('sw-category-detail-base', {
    template,

    inject: ['repositoryFactory', 'context'],

    data() {
        return {
            acris_category_customer_group_exclude_sitemap: false
        };
    },

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    computed: {
        ...mapState('swCategoryDetail', [
            'category'
        ]),

        category() {
            return Shopware.State.get('swCategoryDetail').category;
        },

        categoryRepository() {
            return this.repositoryFactory.create('category');
        },

        identifier() {
            return this.placeholder(this.item, 'id');
        }
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    created() {
        this.refreshSitemapCheckboxValue();
    },

    updated() {
        this.refreshSitemapCheckboxValue();
    },

    methods: {
        refreshSitemapCheckboxValue() {
            if(this.category.customFields != null) {
                this.acris_category_customer_group_exclude_sitemap = this.category.customFields.acris_category_customer_group_exclude_sitemap;
            } else {
                this.acris_category_customer_group_exclude_sitemap = false;
            }
        },

        onChangeExcludeSitemapCheckbox() {
            this.createDefaultCustomFieldsIfNotExists();
            this.category.customFields = {
                acris_category_customer_group_exclude_sitemap: this.acris_category_customer_group_exclude_sitemap
            };
        },

        createDefaultCustomFieldsIfNotExists() {
            if(this.category.customFields == null) {
                this.category.customFields = {
                    acris_category_customer_group_exclude_sitemap: false
                };
            }
        }
    }
});
