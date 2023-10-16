import template from './template.html.twig';
import './style.scss';

const { Component, Data: { Criteria } } = Shopware;

Component.register('neti-store-locator-filter-refresh-index-modal', {
    template,

    inject: [
        'repositoryFactory'
    ],

    data() {
        return {
            isLoading: false,
            isBuilding: false,
            isAbort: false,

            filters: [],

            currentStatus: 'disabled',
            currentIndex: 0,
            buildPercentage: 0
        };
    },

    computed: {
        filterRepository() {
            return this.repositoryFactory.create('neti_sl_filter');
        },

        indexButtonEnabled() {
            return this.isLoading === false && this.filters.length > 0 && this.isBuilding === false;
        },

        abortButtonEnabled() {
            return this.isAbort === false;
        }
    },

    mounted() {
        this.loadFilters();
    },

    methods: {
        onIndex() {
            this.$refs.stepDisplay.items.forEach(item => {
                item.setActive(false);
                item.setVariant('disabled');
            });

            this.isBuilding    = true;
            this.currentIndex  = 0;
            this.currentStatus = 'success';

            this.$nextTick(async() => {
                this.currentStatus = 'disabled';
                this.currentIndex  = 1;

                for (let i = 0; i < this.filters.length; i++) {
                    const filter = this.filters[i];

                    if (this.isAbort) {
                        this.onCloseModal();
                        return;
                    }

                    await this.createIndex(filter.id);

                    this.buildPercentage = Math.round((
                        i + 1
                    ) / this.filters.length * 100);
                }

                this.currentStatus = 'success';

                this.$nextTick(() => {
                    this.currentIndex = 2;
                    this.isBuilding = false;
                });
            });
        },

        createIndex(filterId) {
            let httpClient = Shopware.Application.getContainer('init').httpClient;
            let headers    = {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${ Shopware.Context.api.authToken.access }`,
                'Content-Type': 'application/json'
            };

            return httpClient.post('_action/neti-store-locator/build-filter-values', { id: filterId }, { headers });
        },

        async loadFilters() {
            this.isLoading = true;

            const criteria = new Criteria();

            criteria.addFilter(Criteria.equals('active', true));
            criteria.addFilter(Criteria.equals('valueType', 2));

            this.filters = await this.filterRepository.search(criteria, Shopware.Context.api);

            this.isLoading = false;
        },

        onCloseModal() {
            this.$emit('close');
        },

        onAbort() {
            if (this.isBuilding) {
                this.isAbort = true;
                return;
            }

            this.onCloseModal();
        }
    }
});