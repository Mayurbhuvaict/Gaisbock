const { Component } = Shopware;

Component.extend('pluszwei-faq-manager-article-create', 'pluszwei-faq-manager-article-detail', {

    methods: {
        createdComponent() {
            if (Shopware.Context.api.languageId !== Shopware.Context.api.systemLanguageId) {
                Shopware.State.commit('context/setApiLanguageId', Shopware.Context.api.languageId)
            }

            if (!this.article) {
                if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                    Shopware.State.commit('context/resetLanguageToDefault');
                }
            }

            this.$super('createdComponent');
        },

        getArticle() {
            this.article = this.repository.create(Shopware.Context.api);
            this.isLoading = false;
        }
    }
});
