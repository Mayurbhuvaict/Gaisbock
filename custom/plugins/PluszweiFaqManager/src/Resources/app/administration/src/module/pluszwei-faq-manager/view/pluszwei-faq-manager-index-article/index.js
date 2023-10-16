import template from './pluszwei-faq-manager-index-article.html.twig';
import './pluszwei-faq-manager-index-article.scss';

const { Component, Mixin, Context } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('pluszwei-faq-manager-index-article', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing')
    ],

    props: {
        search: {
            type: String,
            required: false,
            default: null
        }
    },

    data() {
        return {
            articles: null,
            sortBy: 'createdAt',
            sortDirection: 'DESC',
            naturalSorting: true,
            isLoading: false,
            total: 0,
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        articleColumns() {
            return [{
                property: 'title',
                label: this.$tc('pluszwei-faq-manager.article.index.columnTitle'),
                routerLink: 'pluszwei.faq.manager.article.detail',
                allowResize: true,
                primary: true,
                inlineEdit: 'string'
            }, {
                property: 'category.name',
                sortable: true,
                label: this.$tc('pluszwei-faq-manager.article.index.columnCategory'),
                allowResize: true
            }, {
                property: 'section.name',
                sortable: true,
                label: this.$tc('pluszwei-faq-manager.article.index.columnSubcategory'),
                allowResize: true
            }, {
                property: 'active',
                sortable: true,
                label: this.$tc('pluszwei-faq-manager.article.index.columnActive'),
                inlineEdit: 'boolean'
            }, {
                property: 'featured',
                sortable: true,
                label: this.$tc('pluszwei-faq-manager.article.index.columnFeatured'),
                inlineEdit: 'boolean'
            }, {
                property: 'createdAt',
                sortable: true,
                label: this.$tc('pluszwei-faq-manager.article.index.columnDateUpdated')
            }];
        },

        articleRepository() {
            return this.repositoryFactory.create('pluszwei_faq_article');
        },
    },

    created() {
    },

    methods: {
        getCriteria() {
            const criteria = new Criteria(this.page, this.limit);
            criteria.setTerm(this.search);
            criteria.addAssociation('category');
            criteria.addAssociation('section');
            this.naturalSorting = this.sortBy === 'createdAt';

            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting))

            return criteria;
        },

        async getList() {
            this.isLoading = true;

            this.articles = await this.articleRepository.search(this.getCriteria(), Context.api),
            this.total = this.articles.total;
            this.$emit('update-total', {
                total: this.total
            });
            this.isLoading = false;
        },

        async reload() {
            await this.getList();
        },

        getBreadcrumb(item) {
            const category = item.category;
            if (!category) {
                return;
            }

            if (category.breadcrumb) {
                return category.breadcrumb.join(' / ');
            }
            return category.translated.name || category.name;
        }
    }
});
