/* global Shopware */

import template from './sw-product-detail-base.html.twig';
const { Data } = Shopware;
const { Criteria } = Data;
Shopware.Component.override('sw-product-detail-base', {
    template,
    data() {
        return {
            gaisbockProducts: []
        };
    },
    computed:{
        productRepository(){
            return this.repositoryFactory.create('product');
        },
        productIds: {
            get() {
                return this.product.customFields.gaisbockProducts;
            },

            set(productIds) {
                if(productIds === undefined){
                    this.product.customFields.gaisbockProducts = [];
                }else {
                    this.product.customFields.gaisbockProducts = productIds;
                }
            },
        },
    },
    methods: {
        createdComponent() {
            let criteria = new Criteria();
            criteria.addFilter( Criteria.equals('parentId',null));

            this.productRepository.search(criteria).then((searchresult) => {
                this.gaisbockProducts = searchresult;
            });
        },
    }
});
