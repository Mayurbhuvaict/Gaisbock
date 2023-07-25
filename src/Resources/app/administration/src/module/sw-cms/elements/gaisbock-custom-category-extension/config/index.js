import template from './sw-cms-el-config-gaisbock-custom-category-extension.html.twig';

export default {
    template,

    methods: {
        createdComponent() {
            this.initElementConfig('gaisbock-custom-category-extension');
        },
    }
}