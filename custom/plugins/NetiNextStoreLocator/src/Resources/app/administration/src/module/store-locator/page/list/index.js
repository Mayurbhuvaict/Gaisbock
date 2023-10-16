import template from './template.twig';
import './style.scss';

const { Component, Mixin } = Shopware;
const { Criteria }         = Shopware.Data;

Component.register('neti-store-locator-list', {
    template,

    inject: [
        'repositoryFactory',
        'searchTypeService'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('salutation'),
        Mixin.getByName('listing')
    ],

    data() {
        return {
            data: null,

            customers: 0,
            sortBy: 'description',
            sortDirection: 'DESC',
            naturalSorting: true,
            isLoading: false,
            showDeleteModal: false,
            additionalFilters: []
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('neti_store_locator');
        },

        columns() {
            return this.getColumns();
        },

        defaultCriteria() {
            const criteria      = new Criteria(this.page, this.limit);
            this.naturalSorting = this.sortBy === 'description';

            criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));
            criteria.addAssociation('salesChannels');
            criteria.addAssociation('country');

            this.additionalFilters.forEach(filter => criteria.addFilter(filter));

            return criteria;
        }
    },

    methods: {
        getList() {
            this.isLoading = true;

            this.repository.search(this.defaultCriteria, Shopware.Context.api).then((items) => {
                this.total     = items.total;
                this.data      = items;
                this.isLoading = false;

                return items;
            }).catch(() => {
                this.isLoading = false;
            });
        },

        onDelete(id) {
            this.showDeleteModal = id;
        },

        onCloseDeleteModal() {
            this.showDeleteModal = false;
        },

        onConfirmDelete(id) {
            this.showDeleteModal = false;

            return this.repository.delete(id, Shopware.Context.api).then(() => {
                this.getList();
            });
        },

        onChangeLanguage(languageId) {
            this.getList(languageId);
        },

        getColumns() {
            return [
                {
                    property: 'id',
                    dataIndex: 'id',
                    label: 'ID',
                    allowResize: true,
                    visible: false
                },
                {
                    property: 'active',
                    dataIndex: 'active',
                    label: this.$t('neti-store-locator.list.columnActive'),
                    allowResize: true
                },
                {
                    property: 'label',
                    dataIndex: 'label',
                    label: this.$t('neti-store-locator.list.columnLabel'),
                    width: '300px',
                    allowResize: true,
                    primary: true
                },
                {
                    property: 'featured',
                    dataIndex: 'featured',
                    label: this.$t('neti-store-locator.list.columnFeatured'),
                    allowResize: true
                },
                {
                    property: 'salesChannel',
                    dataIndex: 'salesChannel',
                    label: this.$t('neti-store-locator.list.columnSalesChannel'),
                    allowResize: true,
                    visible: false
                },
                {
                    property: 'street',
                    dataIndex: 'street',
                    label: this.$t('neti-store-locator.list.columnStreet'),
                    allowResize: true
                },
                {
                    property: 'streetNumber',
                    dataIndex: 'streetNumber',
                    label: this.$t('neti-store-locator.list.columnStreetNumber'),
                    allowResize: true
                },
                {
                    property: 'zipCode',
                    dataIndex: 'zipCode',
                    label: this.$t('neti-store-locator.list.columnZipCode'),
                    allowResize: true
                },
                {
                    property: 'city',
                    dataIndex: 'city',
                    label: this.$t('neti-store-locator.list.columnCity'),
                    allowResize: true
                },
                {
                    property: 'country',
                    dataIndex: 'country',
                    label: this.$t('neti-store-locator.list.columnCountry'),
                    allowResize: true
                }
            ];
        },

        onFilterChanged(filters) {
            this.additionalFilters = filters;
            this.getList();
        },

        async onDuplicate(item) {
            this.isLoading = true;
            const behavior = {
                cloneChildren: true,
                overwrites: {
                    active: false,
                    label: `${item.label} ${this.$tc('global.default.copy')}`,
                },
            };

            await this.repository.clone(item.id, Shopware.Context.api, behavior);

            this.getList();
        }
    }
});
