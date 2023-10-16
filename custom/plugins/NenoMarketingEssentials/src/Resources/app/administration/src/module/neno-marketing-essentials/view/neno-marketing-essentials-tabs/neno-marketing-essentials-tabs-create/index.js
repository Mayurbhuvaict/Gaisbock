Shopware.Component.extend('neno-marketing-essentials-tabs-create', 'neno-marketing-essentials-tabs-detail', {
    methods: {
        getTab() {
            this.tab = this.repository.create(Shopware.Context.api);

            // Default values
            this.tab.isGlobal = false
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.tab, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({
                        name: 'neno.marketing.essentials.overview.tabsDetail',
                        params: { id: this.tab.id }
                    });
                }) .catch((exception) => {
                console.log(exception);
                this.isLoading = false;

                this.createNotificationError({
                    title: "Test",
                    message: exception
                });
            });
        },
    }
})
