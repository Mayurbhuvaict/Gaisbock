import template from './pluszwei-faq-manager-index.html.twig';
import './pluszwei-faq-manager-index.scss';

const { Component, State } = Shopware;

Component.register('pluszwei-faq-manager-index', {
    template,

    data() {
        return {
            isLoading: false,
            total: 0,
            term: null,
            currentLanguageId: Shopware.Context.api.languageId
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        articleRoute() {
            return {
                name: 'pluszwei.faq.manager.index.article',
                params: { },
                query: { }
            };
        },

        categoryRoute() {
            return {
                name: 'pluszwei.faq.manager.index.category',
                params: { },
                query: { }
            };
        }
    },

    created() {
    },

    filters: {
    },

    methods: {
        onUpdateTotal(data) {
            this.total = data.total;
        },

        onRefresh() {
            this.$refs.contentView.reload();
        },

        onSearch(term) {
            this.term = term;
            let $this = this;
            setTimeout(function () {
                $this.$refs.contentView.reload();
            }, 500);

        },

        changeLanguage(newLanguageId) {
            State.commit('context/setApiLanguageId', newLanguageId);

            this.$refs.contentView.reload();
        },
    }
});
