import template from './template.twig';

Shopware.Component.register('neti-store-locator-general-media-card', {
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
    },
    methods: {
        onPictureMediaSelected(media) {
            this.store.pictureMedia   = media;
            this.store.pictureMediaId = media ? media.id : null;
        }
    }
});
