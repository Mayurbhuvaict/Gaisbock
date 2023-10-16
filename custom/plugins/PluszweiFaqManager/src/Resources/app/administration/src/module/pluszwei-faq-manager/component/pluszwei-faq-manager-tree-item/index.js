import template from './pluszwei-faq-manager-tree-item.html.twig';
const { Component } = Shopware;

Component.extend('pluszwei-faq-manager-tree-item', 'sw-tree-item', {
    template,

    methods: {
        checkAllowDeleteCategories(item) {
            return item.data.childCount < 1;
        },
        checkAllowNewCategories(item) {
            return item.data.level < 2;
        }
    }
});
