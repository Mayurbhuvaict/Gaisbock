import template from './pluszwei-faq-manager-category-tree.html.twig';

const Criteria = Shopware.Data.Criteria;
const { Component } = Shopware;
const { mapState } = Shopware.Component.getComponentHelper();

Component.extend('pluszwei-faq-manager-category-tree', 'sw-category-tree', {
    template,

    props: {
        categoryId: {
            type: String,
            required: false,
            default: null
        },

        currentLanguageId: {
            type: String,
            required: true
        },
    },

    data() {
        return {
            faqCategory: null,
            translationContext: 'sw-category'
        };
    },

    computed: {
        category() {
            return this.faqCategory;
        },
        categoryRepository() {
            return this.repositoryFactory.create('pluszwei_faq_category');
        },
        defaultLayout() {
            return null;
        }
    },

    methods: {
        createdComponent() {
            this.getCategory();
            this.$super('createdComponent');
        },

        changeCategory(category) {
            this.$emit('change-category-id', category.id);
            this.faqCategory = category;
            this.getCategory();
        },

        onFinishEditCategory(editCategory) {
            const category = editCategory.data

            this.categoryRepository.save(category, Shopware.Context.api).then(() => {
                const criteria = new Criteria();
                criteria.setIds([category.id, category.parentId].filter((id) => id !== null));
                this.categoryRepository.search(criteria, Shopware.Context.api).then((categories) => {
                    this.addCategories(categories);
                });
            });
        },

        getCategory() {
            const criteria = new Criteria();

            this.categoryRepository
                .get(this.categoryId, Shopware.Context.api, criteria)
                .then((entity) => {
                    this.faqCategory = entity;
                })

        },

        loadDefaultLayout() {
        },
    },

    watch: {
        category(newVal, oldVal) {
            // load data when path is available
            if (!oldVal && this.isLoadingInitialData) {
                this.openInitialTree();
                return;
            }

            // back to index
            if (newVal === null) {
                return;
            }

            // reload after save
            if (oldVal && newVal.id === oldVal.id) {
                const affectedCategoryIds = [
                    newVal.id
                ];

                const criteria = Criteria.fromCriteria(this.criteria)
                    .setIds(affectedCategoryIds.filter((value, index, self) => {
                        return value !== null && self.indexOf(value) === index;
                    }));

                this.categoryRepository.search(criteria).then((categories) => {
                    this.addCategories(categories);
                });
            }
        }
    }
});
