import template from './sw-cms-el-gaisbock-custom-category-extension.html.twig';
import './sw-cms-el-gaisbock-custom-category-extension.scss';
export default {
    template,

    methods: {
        createdComponent() {
            this.initElementConfig('gaisbock-custom-category-extension');
            this.initElementData('gaisbock-custom-category-extension');
        },
    },
}