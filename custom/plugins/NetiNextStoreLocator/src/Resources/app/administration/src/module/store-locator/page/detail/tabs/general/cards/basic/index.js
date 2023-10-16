import template from './template.twig';

const { Component }         = Shopware;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('neti-store-locator-general-basic-card', {
    template,

    props: {
        store: {
            type: Object,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        },
        salesChannels: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        }
    },
    computed: {
        showAlwaysValues() {
            return [
                { key: 'no' },
                { key: 'top' },
                { key: 'bottom' }
            ];
        },
        ...mapPropertyErrors('store', ['label'])
    },
    methods: {
        onIconMediaSelected(media) {
            this.store.iconMedia   = media;
            this.store.iconMediaId = media ? media.id : null;
        },
        onTagSelected(tags) {
            this.store.tags = tags;
        }
    }
});
