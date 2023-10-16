import template from './sw-promotion-v2-individual-codes-beahvior.html.twig';
import './style.scss';

const { Component, Data: { Criteria }, Context } = Shopware;

Component.override('sw-promotion-v2-individual-codes-behavior', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            reservedCodes: {},
        }
    },

    created() {
        this.fetchReservedCodes();
    },

    methods: {
        fetchReservedCodes() {
            const criteria = new Criteria();
            criteria.addFilter(
                Criteria.equals('promotionId', this.promotion.id)
            );

            this.reservedCodeRepository
                .search(criteria, Context.api)
                .then((res) => {
                    this.reservedCodes = res.reduce((acc, entry) => ({
                        ...acc,
                        [entry.promotionIndividualCodeId]: true,
                    }), {});
                });
        }
    },

    computed: {
        reservedCodeRepository() {
            return this.repositoryFactory.create('reserved_individual_promotion_code');
        }
    }
});
