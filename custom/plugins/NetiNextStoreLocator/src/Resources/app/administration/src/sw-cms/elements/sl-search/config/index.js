import template from './sw-cms-el-config-sl-search.html.twig';

Shopware.Component.register('sw-cms-el-config-sl-search', {
    template,

    mixins: [
        'cms-element'
    ],

    computed: {
        searchParams() {
            return this.element.config.searchParams;
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('sl-search');
        },

        onElementUpdate(element) {
            this.$emit('element-update', element);
        }
    },
});
