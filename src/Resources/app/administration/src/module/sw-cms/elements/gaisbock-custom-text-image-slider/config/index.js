import template from './sw-cms-el-config-gaisbock-custom-text-image-slider.html.twig';

const {Mixin} = Shopware;
const {moveItem, object: {cloneDeep}} = Shopware.Utils;
const Criteria = Shopware.Data.Criteria;
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
            entity: this.element,
            mediaItems: [],
            showSlideConfig: false,
            coverOpenMediaModal: false,
        };
    },

    computed: {
        uploadTag() {
            return `cms-element-media-config-${this.element.id}`;
        },

        mediaRepository() {
            return this.repositoryFactory.create('media');
        },

        defaultFolderName() {
            return this.cmsPageState.pageEntityName;
        },

        items() {
            if (this.element.config && this.element.config.sliderItems && this.element.config.sliderItems.value) {
                return this.element.config.sliderItems.value;
            }

            return [];
        },

        speedDefault() {
            return this.cmsService.getCmsElementConfigByName('image-slider').defaultConfig.speed.value;
        },

        autoplayTimeoutDefault() {
            return this.cmsService.getCmsElementConfigByName('image-slider').defaultConfig.autoplayTimeout.value;
        },

        uploadCoverTag() {
            return `cms-element-media-cover-config-${this.element.id}`;
        },

        previewCoverSource() {
            if (this.element.data && this.element.data.coverMedia && this.element.data.coverMedia.id) {
                return this.element.data.coverMedia;
            }

            return this.element.config.coverMedia.value;
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        async createdComponent() {
            this.initElementConfig('gaisbock-custom-text-image-slider');

            if (this.element.config.autoSlide?.value) {
                this.showSlideConfig = true;
            }

            if (this.element.config.sliderItems.source !== 'default' && this.element.config.sliderItems.value.length > 0) {
                const mediaIds = this.element.config.sliderItems.value.map((configElement) => {
                    return configElement.mediaId;
                });

                const criteria = new Criteria(1, 25);
                criteria.setIds(mediaIds);

                const searchResult = await this.mediaRepository.search(criteria);
                this.mediaItems = mediaIds.map((mediaId) => {
                    return searchResult.get(mediaId);
                });
            }
        },

        onImageUpload(mediaItem) {
            const sliderItems = this.element.config.sliderItems;
            if (sliderItems.source === 'default') {
                sliderItems.value = [];
                sliderItems.source = 'static';
            }

            sliderItems.value.push({
                mediaUrl: mediaItem.url,
                mediaId: mediaItem.id,
                url: null,
                newTab: false,
                text: mediaItem.text,
                buttonText: mediaItem.buttonText,
                buttonUrl: mediaItem.buttonUrl,
                buttonNewTab: mediaItem.buttonNewTab
            });

            this.mediaItems.push(mediaItem);

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        onItemRemove(mediaItem, index) {
            const key = mediaItem.id;
            const {value} = this.element.config.sliderItems;

            this.element.config.sliderItems.value = value.filter(
                (item, i) => {
                    return (item.mediaId !== key || i !== index);
                },
            );

            this.mediaItems = this.mediaItems.filter(
                (item, i) => {
                    return (item.id !== key || i !== index);
                },
            );

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        onCloseMediaModal() {
            this.mediaModalIsOpen = false;
        },

        onCloseCoverMediaModal() {
            this.coverOpenMediaModal = false;
        },

        onMediaSelectionChange(mediaItems) {
            const sliderItems = this.element.config.sliderItems;
            if (sliderItems.source === 'default') {
                sliderItems.value = [];
                sliderItems.source = 'static';
            }

            mediaItems.forEach((item) => {
                this.element.config.sliderItems.value.push({
                    mediaUrl: item.url,
                    mediaId: item.id,
                    url: null,
                    newTab: false,
                    text: item.text,
                    buttonText: item.buttonText,
                    buttonUrl: item.buttonUrl,
                    buttonNewTab: item.buttonNewTab
                });
            });

            this.mediaItems.push(...mediaItems);

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        onItemSort(dragData, dropData) {
            moveItem(this.mediaItems, dragData.position, dropData.position);
            moveItem(this.element.config.sliderItems.value, dragData.position, dropData.position);

            this.updateMediaDataValue();
            this.emitUpdateEl();
        },

        updateMediaDataValue() {
            if (this.element.config.sliderItems.value) {
                const sliderItems = cloneDeep(this.element.config.sliderItems.value);

                sliderItems.forEach((sliderItem) => {
                    this.mediaItems.forEach((mediaItem) => {
                        if (sliderItem.mediaId === mediaItem.id) {
                            sliderItem.media = mediaItem;
                            sliderItem.text = mediaItem.text;
                            sliderItem.buttonText = mediaItem.buttonText;
                            sliderItem.buttonUrl = mediaItem.buttonUrl;
                            sliderItem.buttonNewTab = mediaItem.buttonNewTab;

                        }
                    });
                });

                if (!this.element.data) {
                    this.$set(this.element, 'data', {sliderItems});
                } else {
                    this.$set(this.element.data, 'sliderItems', sliderItems);
                }
            }
        },

        onOpenMediaModal() {
            this.mediaModalIsOpen = true;
        },

        onChangeMinHeight(value) {
            this.element.config.minHeight.value = value === null ? '' : value;

            this.$emit('element-update', this.element);
        },

        onChangeAutoSlide(value) {
            this.showSlideConfig = value;

            if (!value) {
                this.element.config.autoplayTimeout.value = this.autoplayTimeoutDefault;
                this.element.config.speed.value = this.speedDefault;
            }
        },

        onChangeDisplayMode(value) {
            if (value === 'cover') {
                // this.element.config.verticalAlign.value = null;
            }

            this.$emit('element-update', this.element);
        },

        emitUpdateEl() {
            this.$emit('element-update', this.element);
        },
        onBlur(content) {
            this.emitChanges(content);
        },

        onInput(content) {
            this.emitChanges(content);
        },
        onBlurOne(content) {
            this.emitChangesOne(content);
        },

        onInputOne(content) {
            this.emitChangesOne(content);
        },

        emitChanges(content) {
            if (content !== this.element.config.detailTitle.value) {
                this.element.config.detailTitle.value = content;
                this.$emit('element-update', this.element);
            }
        },
        emitChangesOne(content) {
            if (content !== this.element.config.hideContent.value) {
                this.element.config.hideContent.value = content;
                this.$emit('element-update', this.element);
            }
        },

        async onCoverImageUpload({targetId}) {
            const mediaEntity = await this.mediaRepository.get(targetId);
            this.element.config.coverMedia.value = mediaEntity.id;
            this.element.config.coverUrl.value = mediaEntity.url;
            this.updateCoverElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        updateCoverElementData(coverMedia = null) {
            const mediaId = coverMedia === null ? null : coverMedia.id;

            if (!this.element.data) {
                this.$set(this.element, 'data', {mediaId});
                this.$set(this.element, 'data', {coverMedia});
            } else {
                this.$set(this.element.data, 'coverMediaId', mediaId);
                this.$set(this.element.data, 'coverMedia', coverMedia);
            }
        },

        onCoverOpenMediaModal() {
            this.coverOpenMediaModal = true;
        },
        onSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.coverMedia.value = media.id;
            this.element.config.coverMedia.source = 'static';

            this.updateCoverElementData(media);

            this.$emit('element-update', this.element);
        },
        onCoverImageRemove() {
            this.element.config.coverMedia.value = null;
            this.element.config.coverUrl.value = null;

            this.updateImageElementData();

            this.$emit('element-update', this.element);
        },
        updateImageElementData(imageMedia = null) {
            const mediaId = imageMedia === null ? null : imageMedia.id;

            if (!this.element.data) {
                this.$set(this.element, 'data', {mediaId});
                this.$set(this.element, 'data', {imageMedia});
            } else {
                this.$set(this.element.data, 'mediaId', mediaId);
                this.$set(this.element.data, 'imageMedia', imageMedia);
            }
        },

    },
}
