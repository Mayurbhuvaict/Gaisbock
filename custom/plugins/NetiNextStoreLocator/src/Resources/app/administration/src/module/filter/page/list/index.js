import template from './template.twig';
import './style.scss';

const { Component, Mixin } = Shopware;
const { Criteria }         = Shopware.Data;

Component.register('neti-sl-filter-list', {
    template,

    inject: [
        'repositoryFactory',
        'searchTypeService'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder'),
    ],

    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false,

            filters: [],
            deletedFilters: [],

            refreshIndexModal: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('neti_sl_filter');
        },

        valueTypes() {
            return [
                {
                    key: 1,
                    label: this.$t('neti-store-locator.filter.value-type.tag')
                },
                {
                    key: 2,
                    label: this.$t('neti-store-locator.filter.value-type.customField')
                }
            ];
        },

        displayTypes() {
            return [
                {
                    key: 1,
                    label: this.$t('neti-store-locator.filter.display-type.checkbox')
                },
                {
                    key: 2,
                    label: this.$t('neti-store-locator.filter.display-type.radio')
                },
                {
                    key: 3,
                    label: this.$t('neti-store-locator.filter.display-type.select')
                }
            ];
        },

        locale() {
            return this.$root.$i18n.locale;
        },

        fallbackLocale() {
            return this.$root.$i18n.fallbackLocale;
        },

        sortedFilters() {
            return this.filters.sort((a, b) => a.position - b.position);
        },

        customFieldCriteria() {
            const criteria = new Criteria();

            criteria.addFilter(
                Criteria.equals('customFieldSet.relations.entityName', 'neti_store_locator')
            );

            return criteria;
        },

        isDefaultLanguage() {
            return Shopware.Context.api.languageId === Shopware.Defaults.systemLanguageId;
        },

        hasNewFilters() {
            return this.filters.filter(f => f._isNew).length > 0;
        }
    },

    mounted() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.loadFilters();
        },

        loadFilters() {
            const criteria = new Criteria();

            criteria.addAssociation('tags');

            this.isLoading = true;

            this.repository.search(criteria, Shopware.Context.api).then(filters => {
                this.filters        = filters;
                this.deletedFilters = [];

                this.isLoading = false;
            });
        },

        onAddFilter() {
            const filter = this.repository.create();

            filter.active       = false;
            filter.title        = this.$t('neti-store-locator.filter.titlePlaceholder');
            filter.valueType    = 2; // Custom field
            filter.displayType  = 1; // Checkbox
            filter.tags         = [];
            filter.customFields = [];
            filter.position     = this.filters.length > 0 ? (
                this.filters[this.filters.length - 1].position + 1
            ) : 1;

            this.filters.push(filter);
        },

        onRemoveFilter(filter) {
            const index = this.filters.findIndex(f => f.id === filter.id);

            if (index > -1) {
                this.filters.splice(index, 1);

                if (!filter._isNew) {
                    this.deletedFilters.push(filter.id);
                }
            }
        },

        async onSave() {
            // Save filters
            this.isSaveSuccessful = false;
            this.isLoading        = true;

            // ...

            for (let i = 0; i < this.filters.length; i++) {
                await this.repository.save(this.filters[i], Shopware.Context.api);
            }

            for (let i = 0; i < this.deletedFilters.length; i++) {
                await this.repository.delete(this.deletedFilters[i], Shopware.Context.api);
            }

            this.deletedFilters   = [];
            this.isSaveSuccessful = true;

            this.loadFilters();
        },

        onSaveFinish() {
            this.isSaveSuccessful = false;
        },

        onMoveUp(filter) {
            const currentIndex = this.sortedFilters.findIndex(f => f.id === filter.id);
            const prevFilter   = this.sortedFilters[currentIndex - 1];

            if (prevFilter) {
                const position = filter.position;

                filter.position     = prevFilter.position;
                prevFilter.position = position;
            }
        },

        onMoveDown(filter) {
            const currentIndex = this.sortedFilters.findIndex(f => f.id === filter.id);
            const nextFilter   = this.sortedFilters[currentIndex + 1];

            if (nextFilter) {
                const position = filter.position;

                filter.position     = nextFilter.position;
                nextFilter.position = position;
            }
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.createdComponent();
        },

        onRefreshIndex() {
            this.refreshIndexModal = true;
        }
    }
});
