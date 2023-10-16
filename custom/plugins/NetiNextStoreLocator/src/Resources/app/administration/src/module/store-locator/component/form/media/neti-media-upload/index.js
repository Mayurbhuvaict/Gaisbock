import template from './template.twig';

const { Component } = Shopware;

Component.register('neti-media-upload', {
    template,
    inheritAttrs: true,

    inject: ['repositoryFactory'],

    props: {
        uploadTag: {
            type: String,
            required: true
        },
        source: {
            type: [Object, String],
            required: false,
            default: null
        },
        sourceId: {
            type: String,
            required: false,
            default: null
        },
        label: {
            type: String,
            required: false
        },
        defaultFolder: {
            type: String,
            required: false,
            validator(value) {
                return value.length > 0;
            },
            default: null,
        }
    },
    watch: {
        sourceId: {
            immediate: true,
            handler() {
                if (this.sourceId) {
                    this.setMediaItem({ targetId: this.sourceId });
                }
            }
        }
    },
    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        }
    },
    methods: {
        setMediaItem({ targetId }) {
            this.mediaRepository.get(targetId, Shopware.Context.api).then((updatedMedia) => {
                this.$emit('change', updatedMedia);
            });
        },
        onRemoveMedia() {
            this.$emit('change', null);
        },
        onDropMedia(mediaItem) {
            this.setMediaItem({ targetId: mediaItem.id });
        },
        onMediaSelected(items) {
            this.setMediaItem({ targetId: items[0].id });
        }
    }
});
