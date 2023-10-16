import template from './sw-cms-el-solid-ie-feed.html.twig';
import './sw-cms-el-solid-ie-feed.scss';

const { Component, Mixin } = Shopware;

/**
 * @private
 */
Component.register('sw-cms-el-solid-ie-feed', {
    template,

    mixins: [Mixin.getByName('cms-element')],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('solid-ie-feed');
        },
    },
});
