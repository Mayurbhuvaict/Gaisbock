import template from './sw-cms-el-config-gaisbock-image-titles-button.html.twig';

const { Mixin } = Shopware;

export default {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('cms-element'),
    ],

    data() {
        return {
            mediaModalIsOpen: false,
            initialFolderId: null,
        };
    },
    computed: {
        mediaRepository() {
            return this.repositoryFactory.create('media');
        },

        uploadTag() {
            return `cms-element-media-config-${this.element.id}`;
        },
        uploadTagTwo() {
            return `cms-element-media-two-config-${this.element.id}`;
        },

        previewSource() {
            if (this.element?.data?.media?.id) {
                return this.element.data.media;
            }

            return this.element.config.media.value;
        },

        previewSourceTwo() {
            if (this.element.data && this.element.data.mediaTwo && this.element.data.mediaTwo.id) {
                return this.element.data.mediaTwo;
            }

            return this.element.config.mediaTwo.value;
        },
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('gaisbock-image-titles-button');
        },
        async onImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            this.element.config.media.value = mediaEntity.id;
            this.element.config.url.value = mediaEntity.url;
            this.updateElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },

        async onImageUploadTwo({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            this.element.config.mediaTwo.value = mediaEntity.id;
            this.element.config.urlTwo.value = mediaEntity.url;
            this.updateElementDataTwo(mediaEntity);

            this.$emit('element-update', this.element);
        },
        onImageRemove() {
            this.element.config.media.value = null;
            this.element.config.url.value = null;
            this.updateElementData();
            this.$emit('element-update', this.element);
        },

        onImageRemoveTwo() {
            this.element.config.mediaTwo.value = null;
            this.element.config.urlTwo.value = null;

            this.updateElementData();

            this.$emit('element-update', this.element);
        },
        onCloseModal() {
            this.mediaModalIsOpen = false;
        },
        onSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.media.value = media.id;
            this.element.config.url.value = mediaEntity.url;
            this.updateElementData(media);

            this.$emit('element-update', this.element);
        },
        onSelectionChangesTwo(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.mediaTwo.value = media.id;
            this.element.config.urlTwo.value = mediaEntity.url;
            this.updateElementData(media);

            this.$emit('element-update', this.element);
        },
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;

            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId });
                this.$set(this.element, 'data', { media });
            } else {
                this.$set(this.element.data, 'mediaId', mediaId);
                this.$set(this.element.data, 'media', media);
            }
        },

        updateElementDataTwo(mediaTwo = null) {
            const mediaTwoId = mediaTwo === null ? null : mediaTwo.id;

            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaTwoId });
                this.$set(this.element, 'data', { mediaTwo });
            } else {
                this.$set(this.element.data, 'mediaTwoId', mediaTwoId);
                this.$set(this.element.data, 'mediaTwo', mediaTwo);
            }
        },
        onOpenMediaModal() {
            this.mediaModalIsOpen = true;
        },

        onChangeMinHeight(value) {
            this.element.config.minHeight.value = value === null ? '' : value;

            this.$emit('element-update', this.element);
        },
        onChangeDisplayMode(value) {
            if (value === 'cover') {
                this.element.config.verticalAlign.value = null;
            }

            this.$emit('element-update', this.element);
        },
        onBlur(content) {
            this.emitChanges(content);
        },
        onInput(content) {
            this.emitChanges(content);
        },
        emitChanges(content) {
            if (content !== this.element.config.content.value) {
                this.element.config.content.value = content;
                this.$emit('element-update', this.element);
            }
        },
    },
};
