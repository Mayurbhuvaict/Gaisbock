import template from './sw-cms-el-config-faq.html.twig';

const { Component } = Shopware;

Component.register('sw-cms-el-config-faq', {
    template,

    mixins: [
        'cms-element'
    ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('hueb-faq-element');
        }
    }
});
