import template from './pluszwei-faq-manager-article-detail.html.twig';
import './pluszwei-faq-manager-article-detail.scss';

const { Component, Mixin } = Shopware;
const Criteria = Shopware.Data.Criteria;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('pluszwei-faq-manager-article-detail', {
    template,

    inject: [
        'repositoryFactory',
        'systemConfigApiService',
        'seoUrlService'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            article: null,
            isLoading: false,
            maximumMetaTitleCharacter: 160,
            maximumMetaDescriptionCharacter: 160,
            configOptions: {},
            processSuccess: false,
            fileAccept: 'image/*',
            moduleData: this.$route.meta.$module
        };
    },

    computed: {
        categoryCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('level', 1));
            criteria.addFilter(Criteria.equals('parentId', null));
            return criteria;
        },

        sectionCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('parentId', this.article.categoryId))
            return criteria;
        },

        repository() {
            return this.repositoryFactory.create('pluszwei_faq_article');
        },

        mediaItem() {
            return this.article !== null ? this.article.media : null;
        },

        mediaRepository() {
            return this.repositoryFactory.create('media');
        },

        backPath() {
            if (this.$route.query.ids && this.$route.query.ids.length > 0) {
                return {
                    name: 'pluszwei.faq.manager.index.article',
                    query: {
                        ids: this.$route.query.ids,
                        limit: this.$route.query.limit,
                        page: this.$route.query.page
                    }
                };
            }
            return { name: 'pluszwei.faq.manager.index' };
        },

        ...mapPropertyErrors('article', [
            'title',
            'salesChannels'
        ])
    },

    created() {
        this.createdComponent();
    },

    filters: {
    },

    methods: {
        async createdComponent() {
            this.isLoading = true;

            await Promise.all([
                this.getPluginConfig(),
                this.getArticle(),
            ]);

            this.isLoading = false;
        },

        async getPluginConfig() {
            this.maximumMetaTitleCharacter = 160;
            this.maximumMetaDescriptionCharacter = 160;
        },

        async getArticle() {
            const criteria = new Criteria();
            criteria.getAssociation('seoUrls')
                .addFilter(Criteria.equals('isCanonical', true));
            criteria.addAssociation('category');
            criteria.addAssociation('section');
            criteria.addAssociation('salesChannels');

            return this.repository
                .get(this.$route.params.id, Shopware.Context.api, criteria)
                .then((entity) => {
                    this.article = entity;

                    return Promise.resolve();
                });
        },

        async changeLanguage() {
            await this.getArticle();
        },

        async onClickSave() {
            if (!this.article.salesChannels.length) {
                this.createNotificationError({
                    message: this.$tc('pluszwei-faq-manager.notification.missingSalesChannelError')
                });

                return;
            }

            if (!this.article.categoryId ||
                !this.$refs.categoryId.singleSelection) {
                this.createNotificationError({
                    message: this.$tc('pluszwei-faq-manager.notification.missingCategoryError')
                });

                return;
            }

            if (!this.article.sectionId ||
                !this.$refs.sectionId.singleSelection) {
                this.createNotificationError({
                    message: this.$tc('pluszwei-faq-manager.notification.missingSubcategoryError')
                });

                return;
            }

            this.isLoading = true;
            await this.updateSeoUrls();

            this.repository
                .save(this.article, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({ name: 'pluszwei.faq.manager.article.detail', params: {id: this.article.id} });

                    this.createNotificationSuccess({
                        title: this.$tc('pluszwei-faq-manager.notification.titleSaveSuccess'),
                        message: this.$tc('pluszwei-faq-manager.notification.messageSaveSuccess')
                    });
                })
                .catch(exception => {
                    this.isLoading = false;
                });
        },

        onCancel() {
            this.$router.push({ name: 'pluszwei.faq.manager.index.article' });
        },

        onCategoryIdChange() {
            this.article.sectionId = null;
        },

        getBreadcrumb(item) {
            if (item.breadcrumb) {
                return item.breadcrumb.join(' / ');
            }
            return item.translated.name || item.name;
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
