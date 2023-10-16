Shopware.Component.override('sw-import-export-edit-profile-general',  {
    computed: {
        supportedEntities() {
            const data = this.$super('supportedEntities');

            data.push({
                value: 'lae_giftcard',
                label: this.$tc('sw-import-export.profile.giftcardLabel'),
                type: 'import-export',
            });

            return data;
        },
    },
})
