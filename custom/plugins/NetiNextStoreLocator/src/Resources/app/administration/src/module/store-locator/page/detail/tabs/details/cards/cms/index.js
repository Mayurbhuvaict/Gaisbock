import template from './template.html.twig';
import './style.scss';

const { Component, Mixin } = Shopware;
const { Criteria }         = Shopware.Data;

Component.register('neti-store-locator-details-cms-card', {
    template,

    props: {
        store: {
            type: Object,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    inject: [
        'repositoryFactory',
    ],

    mixins: [
        Mixin.getByName('listing'),
        Mixin.getByName('placeholder'),
        Mixin.getByName('notification')
    ],

    data() {
        return {
            assignedFieldIds: [],
            deleteFields: [],
            cmsPageTerm: '',
            deleteFieldSetButtonDisabled: true,
            showMassDeleteModal: false,
            showAddCmsPageModal: false,
            cmsPageSelection: null,
            total: 0,
            showDeleteModal: false,
            sortBy: 'position',
            sortDirection: 'ASC',
            naturalSorting: false,
        };
    },

    computed: {
        storeCmsRepository() {
            return this.repositoryFactory.create('neti_store_cms');
        },

        storeCmsColumns() {
            return this.getStoreCmsColumnsColumns();
        },

        storeCmsCriteria() {
            const criteria = new Criteria();

            if (this.cmsPageTerm) {
                criteria.addFilter(Criteria.contains('cmsPage.name', this.cmsPageTerm));
            }

            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));

            if (this.store.cmsPages.length > 0) {
                criteria.addFilter(Criteria.equalsAny('id', this.store.cmsPages.map(item => item.id)));
            } else {
                criteria.addFilter(Criteria.equals('id', this.store.id));
            }

            criteria.addAssociation('cmsPage');

            return criteria;
        }
    },

    methods: {
        sortFields() {
            this.store.cmsPages.sort((a, b) => {
                return a.position - b.position;
            });
        },

        moveFieldUp(storeCms, key) {
            storeCms.position = storeCms.position - 1;

            const previousFormField    = this.store.cmsPages[key - 1];
            previousFormField.position = key;

            this.sortFields();
        },

        moveFieldDown(storeCms, key) {
            storeCms.position = storeCms.position + 1;

            const previousFormField    = this.store.cmsPages[key + 1];
            previousFormField.position = key;

            this.sortFields();
        },

        isFirstField(key) {
            return key === 0;
        },

        isLastField(key) {
            return key === this.store.cmsPages.length - 1;
        },

        onFieldSetTermChanged(term) {
            this.cmsPageTerm            = term;
            this.store.cmsPages.criteria = this.storeCmsCriteria;

            this.$refs.cmsStoreGrid.doSearch();
        },

        onSortColumn(column) {
            if (this.sortBy === column.dataIndex) {
                this.sortDirection = (this.sortDirection === 'ASC' ? 'DESC' : 'ASC');
            } else {
                this.naturalSorting = column.naturalSorting;
                this.sortBy         = column.dataIndex;
                this.sortDirection  = 'ASC';
            }

            this.store.cmsPages.criteria = this.storeCmsCriteria;

            this.$refs.cmsStoreGrid.doSearch();
        },

        onMassDelete() {
            this.showMassDeleteModal = true;
        },

        onAddFieldSetButtonClicked() {
            this.showAddCmsPageModal = true;
        },

        onAssigmentModalClose() {
            this.showAddCmsPageModal = false;
        },

        onCmsPageGridSelectionChanged(selection, selectionCount) {
            this.cmsPageSelection             = selection;
            this.deleteFieldSetButtonDisabled = selectionCount <= 0;
        },

        onDelete(id) {
            this.showDeleteModal = id;
        },

        onCloseDeleteModal() {
            this.showDeleteModal     = false;
            this.showMassDeleteModal = false;
        },

        onConfirmDeleteSet(set) {
            this.removeSet(set);

            this.onCloseDeleteModal();
        },

        onDeleteSets() {
            Object.values(this.cmsPageSelection).forEach((set) => {
                this.removeSet(set);
            });

            this.cmsPageSelection = null;
            this.$refs.cmsStoreGrid.resetSelection();

            this.onCloseDeleteModal();
        },

        getStoreCmsColumnsColumns() {
            return [
                {
                    property: 'cmsPage.name',
                    dataIndex: 'cmsPage.name',
                    label: this.$t('neti-store-locator.detail.cms-stores.column.name'),
                    primary: true
                },
                {
                    property: 'position',
                    dataIndex: 'position',
                    label: this.$t('neti-store-locator.detail.cms-stores.column.position'),
                    primary: true
                },
            ];
        },

        removeSet(storeCms) {
            if (storeCms._isNew) {
                this.store.cmsPages =  this.store.cmsPages.filter(set => {
                    return set.cmsPageId !== storeCms.cmsPageId;
                });

                return;
            }

            this.store.cmsPages.remove(storeCms.id);

            this.$emit('delete-field', storeCms);
        }
    }
});