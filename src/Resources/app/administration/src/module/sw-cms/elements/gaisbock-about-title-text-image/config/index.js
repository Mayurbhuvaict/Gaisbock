import template from './sw-cms-el-config-gaisbock-about-title-text-image.html.twig';
import './sw-cms-el-config-gaisbock-about-title-text-image.scss';

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
            aboutMediaModalIsOpen: false,
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

        previewSource() {
            if (this.element.data && this.element.data.titleMedia && this.element.data.titleMedia.id) {
                return this.element.data.titleMedia;
            }
            return this.element.config.titleMedia.value;
        },

        aboutImagePreviewSource() {
            if (this.element.data && this.element.data.aboutMedia && this.element.data.aboutMedia.id) {
                return this.element.data.aboutMedia;
            }
            return this.element.config.aboutMedia.value;
        },
    },
    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('gaisbock-about-title-text-image');
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

        async onImageUpload({targetId}) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.titleMedia.value = mediaEntity.id;

            this.updateElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },

        onImageRemove() {
            this.element.config.titleMedia.value = null;

            this.updateElementData();

            this.$emit('element-update', this.element);
        },

        onCloseModal() {
            this.mediaModalIsOpen = false;
        },

        onSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.titleMedia.value = media.id;

            this.updateElementData(media);

            this.$emit('element-update', this.element);
        },

        onOpenMediaModal() {
            this.mediaModalIsOpen = true;
        },

        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;

            if (!this.element.data) {
                this.$set(this.element, 'data', {mediaId});
                this.$set(this.element, 'data', {media});
            } else {
                this.$set(this.element.data, 'titleMediaId', mediaId);
                this.$set(this.element.data, 'titleMedia', media);
            }
        },

        async onAboutImageUpload({targetId}) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.aboutMedia.value = mediaEntity.id;

            this.updateAboutImageElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },

        onAboutImageRemove() {
            this.element.config.aboutMedia.value = null;

            this.updateAboutImageElementData();

            this.$emit('element-update', this.element);
        },

        onAboutCloseModal() {
            this.aboutMediaModalIsOpen = false;
        },

        onAboutSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.aboutMedia.value = media.id;

            this.updateAboutImageElementData(media);

            this.$emit('element-update', this.element);
        },

        onOpenAboutMediaModal() {
            this.aboutMediaModalIsOpen = true;
        },

        updateAboutImageElementData(media = null) {
            const mediaId = media === null ? null : media.id;

            if (!this.element.data) {
                this.$set(this.element, 'data', {mediaId});
                this.$set(this.element, 'data', {media});
            } else {
                this.$set(this.element.data, 'aboutMediaId', mediaId);
                this.$set(this.element.data, 'aboutMedia', media);
            }
        },
    },
}