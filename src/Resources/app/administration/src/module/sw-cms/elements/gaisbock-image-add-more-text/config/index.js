import template from './sw-cms-el-config-gaisbock-image-add-more-text.html.twig';
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
            mediaOneModalIsOpen: false,
            mediaTwoModalIsOpen: false,
            mediaThreeModalIsOpen: false,
            mediaFourModalIsOpen: false,
            mediaFiveModalIsOpen: false,
            mediaSixModalIsOpen: false,
            mediaSevenModalIsOpen: false,
            mediaEightModalIsOpen: false,
            mediaNineModalIsOpen: false,
            mediaTenModalIsOpen: false,
            mediaElevenModalIsOpen: false,
            mediaTwelveModalIsOpen: false,
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
        uploadTagOne() {
            return `cms-element-media-one-config-${this.element.id}`;
        },
        uploadTagTwo() {
            return `cms-element-media-two-config-${this.element.id}`;
        },
        uploadTagThree() {
            return `cms-element-media-three-config-${this.element.id}`;
        },
        uploadTagFour() {
            return `cms-element-media-four-config-${this.element.id}`;
        },
        uploadTagFive() {
            return `cms-element-media-five-config-${this.element.id}`;
        },
        uploadTagSix() {
            return `cms-element-media-Six-config-${this.element.id}`;
        },
        uploadTagSeven() {
            return `cms-element-media-seven-config-${this.element.id}`;
        },
        uploadTagEight() {
            return `cms-element-media-eight-config-${this.element.id}`;
        },
        uploadTagNine() {
            return `cms-element-media-nine-config-${this.element.id}`;
        },
        uploadTagTen() {
            return `cms-element-media-ten-config-${this.element.id}`;
        },
        uploadTagEleven() {
            return `cms-element-media-eleven-config-${this.element.id}`;
        },
        uploadTagTwelve() {
            return `cms-element-media-twelve-config-${this.element.id}`;
        },
        previewSource() {
            if (this.element?.data?.media?.id) {
                return this.element.data.media;
            }

            return this.element.config.media.value;
        },
        previewOne() {
            if (this.element?.data?.one?.id) {
                return this.element.data.one;
            }

            return this.element.config.one.value;
        },
        previewTwo() {
            if (this.element?.data?.two?.id) {
                return this.element.data.two;
            }

            return this.element.config.two.value;
        },
        previewThree() {
            if (this.element?.data?.three?.id) {
                return this.element.data.three;
            }

            return this.element.config.three.value;
        },
        previewFour() {
            if (this.element?.data?.four?.id) {
                return this.element.data.four;
            }

            return this.element.config.four.value;
        },
        previewFive() {
            if (this.element?.data?.five?.id) {
                return this.element.data.five;
            }

            return this.element.config.five.value;
        },
        previewSix() {
            if (this.element?.data?.six?.id) {
                return this.element.data.six;
            }

            return this.element.config.six.value;
        },
        previewSeven() {
            if (this.element?.data?.seven?.id) {
                return this.element.data.seven;
            }

            return this.element.config.seven.value;
        },
        previewEight() {
            if (this.element?.data?.eight?.id) {
                return this.element.data.eight;
            }

            return this.element.config.eight.value;
        },
        previewTen() {
            if (this.element?.data?.ten?.id) {
                return this.element.data.ten;
            }

            return this.element.config.ten.value;
        },
        previewNine() {
            if (this.element?.data?.nine?.id) {
                return this.element.data.nine;
            }

            return this.element.config.nine.value;
        },
        previewEleven() {
            if (this.element?.data?.eleven?.id) {
                return this.element.data.eleven;
            }

            return this.element.config.eleven.value;
        },
        previewTwelve() {
            if (this.element?.data?.twelve?.id) {
                return this.element.data.twelve;
            }

            return this.element.config.twelve.value;
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('gaisbock-listpage-text-image');
        },
//**************************//
        async onImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.media.value = mediaEntity.id;
            this.element.config.url.value = mediaEntity.url;

            this.updateElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onOneImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.one.value = mediaEntity.id;
            this.element.config.urlOne.value = mediaEntity.url;
            this.updateOneElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onTwoImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.two.value = mediaEntity.id;
            this.element.config.urlTwo.value = mediaEntity.url;

            this.updateTwoElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onThreeImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.three.value = mediaEntity.id;
            this.element.config.three.source = 'static';
            this.element.config.urlThree.value = mediaEntity.url;

            this.updateThreeElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onFourImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.four.value = mediaEntity.id;
            this.element.config.four.source = 'static';
            this.element.config.urlFour.value = mediaEntity.url;

            this.updateFourElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onFiveImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.five.value = mediaEntity.id;
            this.element.config.five.source = 'static';
            this.element.config.urlFive.value = mediaEntity.url;

            this.updateFiveElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onSixImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.six.value = mediaEntity.id;
            this.element.config.six.source = 'static';
            this.element.config.urlSix.value = mediaEntity.url;

            this.updateSixElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onSevenImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.seven.value = mediaEntity.id;
            this.element.config.seven.source = 'static';
            this.element.config.urlSeven.value = mediaEntity.url;

            this.updateSevenElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onEightImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.eight.value = mediaEntity.id;
            this.element.config.eight.source = 'static';
            this.element.config.urlEight.value = mediaEntity.url;

            this.updateEightElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onNineImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.nine.value = mediaEntity.id;
            this.element.config.nine.source = 'static';
            this.element.config.urlNine.value = mediaEntity.url;

            this.updateNineElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onTenImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.ten.value = mediaEntity.id;
            this.element.config.ten.source = 'static';
            this.element.config.urlTen.value = mediaEntity.url;

            this.updateTenElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onElevenImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.eleven.value = mediaEntity.id;
            this.element.config.eleven.source = 'static';
            this.element.config.urlEleven.value = mediaEntity.url;

            this.updateElevenElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
        async onTwelveImageUpload({ targetId }) {
            const mediaEntity = await this.mediaRepository.get(targetId);

            this.element.config.twelve.value = mediaEntity.id;
            this.element.config.twelve.source = 'static';
            this.element.config.urlTwelve.value = mediaEntity.url;

            this.updateTwelveElementData(mediaEntity);

            this.$emit('element-update', this.element);
        },
//***********************//
        onImageRemove() {
            this.element.config.media.value = null;

            this.updateElementData();

            this.$emit('element-update', this.element);
        },
        onOneImageRemove() {
            this.element.config.one.value = null;

            this.updateOneElementData();

            this.$emit('element-update', this.element);
        },
        onTwoImageRemove() {
            this.element.config.two.value = null;

            this.updateTwoElementData();

            this.$emit('element-update', this.element);
        },
        onThreeImageRemove() {
            this.element.config.three.value = null;

            this.updateThreeElementData();

            this.$emit('element-update', this.element);
        },
        onFourImageRemove() {
            this.element.config.four.value = null;

            this.updateFourElementData();

            this.$emit('element-update', this.element);
        },
        onFiveImageRemove() {
            this.element.config.five.value = null;

            this.updateFiveElementData();

            this.$emit('element-update', this.element);
        },
        onSixImageRemove() {
            this.element.config.six.value = null;

            this.updateSixElementData();

            this.$emit('element-update', this.element);
        },
        onSevenImageRemove() {
            this.element.config.seven.value = null;

            this.updateSevenElementData();

            this.$emit('element-update', this.element);
        },
        onEightImageRemove() {
            this.element.config.eight.value = null;

            this.updateEightElementData();

            this.$emit('element-update', this.element);
        },
        onNineImageRemove() {
            this.element.config.nine.value = null;

            this.updateNineElementData();

            this.$emit('element-update', this.element);
        },
        onTenImageRemove() {
            this.element.config.ten.value = null;

            this.updateTenElementData();

            this.$emit('element-update', this.element);
        },
        onElevenImageRemove() {
            this.element.config.eleven.value = null;

            this.updateElevenElementData();

            this.$emit('element-update', this.element);
        },
        onTwelveImageRemove() {
            this.element.config.twelve.value = null;

            this.updateTwelveElementData();

            this.$emit('element-update', this.element);
        },
//*************************************//
        onCloseModal() {
            this.mediaModalIsOpen = false;
        },
        onOneCloseModal() {
            this.mediaOneModalIsOpen = false;
        },
        onTwoCloseModal() {
            this.mediaTwoModalIsOpen = false;
        },
        onFourCloseModal(){
          this.mediaFourModalIsOpen = false;
        },
        onThreeCloseModal() {
            this.mediaThreeModalIsOpen = false;
        },
        onFiveCloseModal() {
            this.mediaFiveModalIsOpen = false;
        },
        onSixCloseModal() {
            this.mediaSixModalIsOpen = false;
        },
        onSevenCloseModal() {
            this.mediaSevenModalIsOpen = false;
        },
        onEightCloseModal() {
            this.mediaEightModalIsOpen = false;
        },
        onNineCloseModal() {
            this.mediaNineModalIsOpen = false;
        },
        onTenCloseModal() {
            this.mediaTenModalIsOpen = false;
        },
        onElevenCloseModal() {
            this.mediaElevenModalIsOpen = false;
        },
        onTwelveCloseModal() {
            this.mediaTwelveModalIsOpen = false;
        },
//*********************************************//
        onSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.media.value = media.id;
            this.element.config.media.source = 'static';
            this.element.config.url.value = media.url;

            this.updateElementData(media);

            this.$emit('element-update', this.element);
        },
        onOneSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.one.value = media.id;
            this.element.config.one.source = 'static';
            this.element.config.urlOne.value = media.url;
            this.updateOneElementData(media);

            this.$emit('element-update', this.element);
        },
        onTwoSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.two.value = media.id;
            this.element.config.two.source = 'static';
            this.element.config.urlTwo.value = media.url;

            this.updateTwoElementData(media);

            this.$emit('element-update', this.element);
        },
        onThreeSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.three.value = media.id;
            this.element.config.three.source = 'static';
            this.element.config.urlThree.value = media.url;

            this.updateThreeElementData(media);

            this.$emit('element-update', this.element);
        },
        onFourSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.four.value = media.id;
            this.element.config.four.source = 'static';
            this.element.config.urlFour.value = media.url;

            this.updateFourElementData(media);

            this.$emit('element-update', this.element);
        },
        onFiveSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.five.value = media.id;
            this.element.config.five.source = 'static';
            this.element.config.urlFive.value = media.url;


            this.updateFiveElementData(media);

            this.$emit('element-update', this.element);
        },
        onSixSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.six.value = media.id;
            this.element.config.six.source = 'static';
            this.element.config.urlSix.value = media.url;

            this.updateSixElementData(media);

            this.$emit('element-update', this.element);
        },
        onSevenSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.seven.value = media.id;
            this.element.config.seven.source = 'static';
            this.element.config.urlSeven.value = media.url;

            this.updateSevenElementData(media);

            this.$emit('element-update', this.element);
        },
        onEightSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.eight.value = media.id;
            this.element.config.eight.source = 'static';
            this.element.config.urlEight.value = media.url;

            this.updateEightElementData(media);

            this.$emit('element-update', this.element);
        },
        onNineSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.nine.value = media.id;
            this.element.config.nine.source = 'static';
            this.element.config.urlNine.value = media.url;

            this.updateNineElementData(media);

            this.$emit('element-update', this.element);
        },
        onElevenSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.eleven.value = media.id;
            this.element.config.eleven.source = 'static';
            this.element.config.urlEleven.value = media.url;

            this.updateFourElementData(media);

            this.$emit('element-update', this.element);
        },
        onTenSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.ten.value = media.id;
            this.element.config.ten.source = 'static';
            this.element.config.urlTen.value = media.url;

            this.updateTenElementData(media);

            this.$emit('element-update', this.element);
        },
        onTwelveSelectionChanges(mediaEntity) {
            const media = mediaEntity[0];
            this.element.config.twelve.value = media.id;
            this.element.config.twelve.source = 'static';
            this.element.config.urlTwelve.value = media.url;

            this.updateTwelveElementData(media);

            this.$emit('element-update', this.element);
        },
//************************//
        updateElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'mediaId', mediaId);
                this.$set(this.element.data, 'media', media);
            }
        },
        updateOneElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'oneId', mediaId);
                this.$set(this.element.data, 'one', media);
            }
        },
        updateTwoElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'twoId', mediaId);
                this.$set(this.element.data, 'two', media);
            }
        },
        updateThreeElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'threeId', mediaId);
                this.$set(this.element.data, 'three', media);
            }
        },
        updateFourElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'fourId', mediaId);
                this.$set(this.element.data, 'four', media);
            }
        },
        updateFiveElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'fiveId', mediaId);
                this.$set(this.element.data, 'five', media);
            }
        },
        updateSixElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'sixId', mediaId);
                this.$set(this.element.data, 'six', media);
            }
        },
        updateSevenElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'SevenId', mediaId);
                this.$set(this.element.data, 'Seven', media);
            }
        },
        updateEightElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'eightId', mediaId);
                this.$set(this.element.data, 'eight', media);
            }
        },
        updateNineElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'nineId', mediaId);
                this.$set(this.element.data, 'nine', media);
            }
        },
        updateTenElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'tenId', mediaId);
                this.$set(this.element.data, 'ten', media);
            }
        },
        updateElevenElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'elevenId', mediaId);
                this.$set(this.element.data, 'eleven', media);
            }
        },
        updateTwelveElementData(media = null) {
            const mediaId = media === null ? null : media.id;
            if (!this.element.data) {
                this.$set(this.element, 'data', { mediaId, media });
            } else {
                this.$set(this.element.data, 'twelveId', mediaId);
                this.$set(this.element.data, 'twelve', media);
            }
        },

//*********************************//
        onOpenMediaModal() {
            this.mediaModalIsOpen = true;
        },
        onOpenOneMediaModal() {
            this.mediaOneModalIsOpen = true;
        },
        onOpenTwoMediaModal() {
            this.mediaTwoModalIsOpen = true;
        },
        onOpenThreeMediaModal() {
            this.mediaThreeModalIsOpen = true;
        },
        onOpenFourMediaModal() {
            this.mediaFourModalIsOpen = true;
        },
        onOpenFiveMediaModal() {
            this.mediaFiveModalIsOpen = true;
        },
        onOpenSixMediaModal() {
            this.mediaSixModalIsOpen = true;
        },
        onOpenSevenMediaModal() {
            this.mediaSevenModalIsOpen = true;
        },
        onOpenEightMediaModal() {
            this.mediaEightModalIsOpen = true;
        },
        onOpenNineMediaModal() {
            this.mediaNineModalIsOpen = true;
        },
        onOpenTenMediaModal() {
            this.mediaTenModalIsOpen = true;
        },
        onOpenElevenMediaModal() {
            this.mediaElevenModalIsOpen = true;
        },
        onOpenTwelveMediaModal(){
            this.mediaTwelveModalIsOpen = true;
        },
//**********************************//
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
}