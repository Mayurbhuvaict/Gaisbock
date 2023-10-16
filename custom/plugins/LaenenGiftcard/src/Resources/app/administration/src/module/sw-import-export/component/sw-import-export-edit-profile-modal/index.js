Shopware.Component.override('sw-import-export-edit-profile-modal', {
    created() {
        this.supportedEntities.push({
            value: 'lae_giftcard',
            label: this.$tc('sw-import-export.profile.giftcardLabel'),
            type: 'import-export',
        });
    }
});
