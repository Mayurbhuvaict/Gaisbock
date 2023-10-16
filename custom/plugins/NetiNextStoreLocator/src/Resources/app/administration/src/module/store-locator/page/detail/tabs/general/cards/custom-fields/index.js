import template from "./template.twig";
import './config-icon.scss';

const { Component } = Shopware;
const { Criteria }  = Shopware.Data;

Component.register("neti-store-locator-general-custom-fields", {
    template,

    inject: [
        "repositoryFactory",
    ],

    props: {
        store: {
            type: Object,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        },
    },

    data() {
        return {
            customFieldSets: []
        };
    },

    computed: {
        customFieldSetRepository() {
            return this.repositoryFactory.create("custom_field_set");
        },

        customFieldSetCriteria() {
            const customFieldsCriteria = new Criteria(1, 100);
            customFieldsCriteria.addSorting(Criteria.sort("config.customFieldsPosition"));

            const criteria = new Criteria(1, 100);
            criteria.addAssociation("customFields", customFieldsCriteria);
            criteria.addFilter(Criteria.equals("relations.entityName", "neti_store_locator"));

            return criteria;
        },
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.customFieldSetRepository.search(this.customFieldSetCriteria, Shopware.Context.api).then((result) => {
                this.customFieldSets = result;
            });
        },
        onConfigOpen() {
            const me = this;

            me.$router.push({
                name: 'sw.settings.custom.field.index',
            });
        },
    }
});
