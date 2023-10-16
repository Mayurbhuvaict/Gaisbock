import template from './sw-cms-el-config-solid-ie-feed.html.twig';

const { Component, Mixin } = Shopware;

/**
 * @private
 */
Component.register('sw-cms-el-config-solid-ie-feed', {
    template,

    mixins: [Mixin.getByName('cms-element')],

    data() {
        return {
            gridMaxPostCountOptions: ['4', '9', '12'],
            rowMaxPostCountOptions: ['3', '4', '6'],
        };
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('solid-ie-feed');
        },

        onChangeLayout(value) {
            switch (value) {
                case 'grid':
                    this.element.config.maxPostCount.value =
                        this.gridMaxPostCountOptions[0];
                    break;

                case 'row':
                    this.element.config.maxPostCount.value =
                        this.rowMaxPostCountOptions[0];
                    break;

                default:
                    break;
            }
        },
    },
});
