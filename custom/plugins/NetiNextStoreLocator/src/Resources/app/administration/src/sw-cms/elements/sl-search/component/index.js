import template from './sw-cms-el-sl-search.html.twig';
import './sw-cms-el-sl-search.scss';

Shopware.Component.register('sw-cms-el-sl-search', {
    template,

    mixins: [
        'cms-element'
    ],

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('sl-search');
        }
    }
});
