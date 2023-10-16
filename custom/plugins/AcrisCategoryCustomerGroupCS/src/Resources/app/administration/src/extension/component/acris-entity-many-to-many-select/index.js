const { Component } = Shopware;

Component.extend('acris-entity-many-to-many-select', 'sw-entity-many-to-many-select', {

    updated() {
        this.updatedComponent();
    },

    methods: {
        updatedComponent() {
            this.initData();
        }
    }
});
