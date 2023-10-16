import template from './neno-marketing-essentials-tabs-detail.html.twig';

const { Component, Mixin } = Shopware;

Component.register('neno-marketing-essentials-tabs-detail', {
    template,

    inject: [
        'repositoryFactory'
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            tab: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('neno_marketing_essentials_tabs');
        this.getTab();
    },

    methods: {
        getTab() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((tab) => {
                    this.tab = tab;
                });
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.tab, Shopware.Context.api)
                .then(() => {
                    this.getTab();
                    this.isLoading = false;
                    this.processSuccess = true
                }) .catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: 'error',
                    message: exception
                });
            });
        },

        saveFinish() {
            this.processSuccess = false;
        },
    }
});

