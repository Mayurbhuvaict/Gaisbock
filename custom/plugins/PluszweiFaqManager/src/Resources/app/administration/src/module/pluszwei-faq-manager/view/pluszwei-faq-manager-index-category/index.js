import template from './pluszwei-faq-manager-index-category.html.twig';
import './pluszwei-faq-manager-index-category.scss';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('pluszwei-faq-manager-index-category', {
    template,

    inject: [
        'repositoryFactory',
        'seoUrlService'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    shortcuts: {
        'SYSTEMKEY+S': {
            active() {
                return true;
            },
            method: 'onSave'
        },
        ESCAPE: 'cancelEdit'
    },

    data() {
        return {
            categoryId: null,
            total: 0,
            isLoading: false,
            currentLanguageId: Shopware.Context.api.languageId,
            category: null,
            isDisplayingLeavePageWarning: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    computed: {
        categoryRepository() {
            return this.repositoryFactory.create('pluszwei_faq_category');
        },

        showEmptyState() {
            return this.category === null;
        },

        disableCreateButton() {
            return Shopware.Context.api.languageId !== Shopware.Context.api.systemLanguageId;
        },

        ...mapPropertyErrors('category', [
            'name',
            'salesChannels'
        ])
    },

    methods: {
        async reload() {
            this.currentLanguageId = Shopware.Context.api.languageId;
            await this.getCategory();
        },

        async changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            await this.getCategory();
        },

        async changeCategoryId(categoryId) {
            if (categoryId && categoryId !== this.categoryId) {
                this.categoryId = categoryId;
                if (this.category && this.categoryRepository.hasChanges(this.category)) {
                    this.isDisplayingLeavePageWarning = true;
                    return;
                }

                await this.getCategory();
            }
        },

        async getCategory() {
            this.isLoading = true;
            const criteria = new Criteria();
            criteria.getAssociation('seoUrls')
                .addFilter(Criteria.equals('isCanonical', true));
            criteria.addAssociation('salesChannels');

            return this.categoryRepository
                .get(this.categoryId, Shopware.Context.api, criteria)
                .then((entity) => {
                    this.category = entity;
                    this.isLoading = false;
                    return Promise.resolve();
                })
        },

        onLeaveModalClose() {
            this.categoryId = this.category.id;
            this.isDisplayingLeavePageWarning = false;
        },

        async onLeaveModalConfirm() {
            await this.getCategory();
            this.isDisplayingLeavePageWarning = false;
        },

        newCategory() {
            this.category = this.categoryRepository.create(Shopware.Context.api);
        },

        onCancel() {
            this.categoryId = null;
            this.category = null;
        },

        async onClickSave() {
            if (!this.category.salesChannels.length) {
                this.createNotificationError({
                    message: this.$tc('pluszwei-faq-manager.notification.missingSalesChannelError')
                });

                return;
            }

            this.isLoading = true;
            await this.updateSeoUrls();

            this.categoryRepository
                .save(this.category, Shopware.Context.api)
                .then(async () => {
                    this.isLoading = false;

                    this.createNotificationSuccess({
                        title: this.$tc('pluszwei-faq-manager.notification.titleSaveSuccess'),
                        message: this.$tc('pluszwei-faq-manager.notification.messageSaveSuccess')
                    });

                    this.categoryId = this.category.id;
                    await this.getCategory();
                    this.$refs.categoryTree.changeCategory(this.category);
                })
                .catch(() => {
                    this.isLoading = false;
                });
        },

        updateSeoUrls() {
            if (!Shopware.State.list().includes('swSeoUrl')) {
                return Promise.resolve();
            }

            const seoUrls = Shopware.State.getters['swSeoUrl/getNewOrModifiedUrls']();
            return Promise.all(seoUrls.map((seoUrl) => {
                if (seoUrl.seoPathInfo) {
                    seoUrl.isModified = true;
                    return this.seoUrlService.updateCanonicalUrl(seoUrl, seoUrl.languageId);
                }

                return Promise.resolve();
            }));
        },
    }
});
