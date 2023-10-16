import template from './template.html.twig';

const { Component, Mixin }           = Shopware;
const { EntityCollection, Criteria } = Shopware.Data;

Component.register('neti-store-locator-details-cms-card-add-cms-modal', {
    template,

    props: {
        store: {
            type: Object,
            required: true
        }
    },

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    data() {
        return {
            isLoading: false,
            newCmsPages: [],
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('cms_page');
        },

        storeCmsRepository() {
            return this.repositoryFactory.create('neti_store_cms');
        },

        cmsPageCriteria() {
            const criteria = new Criteria();

            if (this.store.cmsPages.length > 0) {
                criteria.addFilter(
                    Criteria.not(
                        'AND',
                        [
                            Criteria.equalsAny('id', this.store.cmsPages.map(item => item.cmsPageId)),
                        ]
                    )
                );
            }

            criteria.addFilter(Criteria.equals('type', 'neti_store_locator'));

            return criteria;
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.newCmsPages = new EntityCollection(
                this.repository.route,
                this.repository.entityName,
                Shopware.Context.api,
            );
        },

        onSave() {
            this.isLoading = true;

            this.newCmsPages.forEach(cmsPage => {
                const storeCms       = this.storeCmsRepository.create(Shopware.Context.api);
                storeCms.cmsPageId = cmsPage.id;
                storeCms.storeId   = this.store.id;
                storeCms.cmsPage   = cmsPage;
                storeCms.position  = this.store.cmsPages.length;

                this.store.cmsPages.add(storeCms)
            });

            this.newCmsPages = [];
            this.isLoading = false;

            this.$emit('modal-close');
        },

        onAbortButtonClick() {
            this.$emit('modal-close');
        },

        isSelected(item) {
            return this.newCmsPages.has(item.id);
        },
    }
});
