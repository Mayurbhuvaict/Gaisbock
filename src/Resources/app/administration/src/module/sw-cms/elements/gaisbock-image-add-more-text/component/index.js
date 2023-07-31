import template from './sw-cms-el-gaisbock-image-add-more-text.html.twig';
import CMS from "../../../constant/sw-cms.constant";
import './sw-cms-el-gaisbock-image-add-more-text.scss';
const { Mixin, Filter } = Shopware;
export default {
    template,
    mixins: [
        Mixin.getByName('cms-element'),
    ],
    data() {
        return {
            editable: true,
            demoValue: '',
        };
    },
    computed: {
        displayModeClass() {
            if (this.element.config.displayMode.value === 'standard') {
                return null;
            }

            return `is--${this.element.config.displayMode.value}`;
        },

        styles() {
            return {
                'min-height': this.element.config.displayMode.value === 'cover' &&
                    this.element.config.minHeight.value &&
                    this.element.config.minHeight.value !== 0
            };
        },

        imgStyles() {
            return {
                'align-self': this.element.config.verticalAlign.value || null,
            };
        },

        mediaUrl() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.media;
            const elemConfig = this.element.config.media;

            if (elemConfig.source === 'mapped') {
                const demoMedia = this.getDemoValue(elemConfig.value);

                if (demoMedia?.url) {
                    return demoMedia.url;
                }

                return staticFallBackImage;
            }

            if (elemConfig.source === 'default') {
                // use only the filename
                const fileName = elemConfig.value.slice(elemConfig.value.lastIndexOf('/') + 1);
                return this.assetFilter(`/administration/static/img/cms/${fileName}`);
            }

            if (elemData?.id) {
                return this.element.data.media.url;
            }

            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }

            return staticFallBackImage;
        },
        one() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.one;
            const elemConfig = this.element.config.one;

            if (elemData?.id) {
                return this.element.data.one.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        two() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.two;
            const elemConfig = this.element.config.two;

            if (elemData?.id) {
                return this.element.data.two.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        three() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.three;
            const elemConfig = this.element.config.three;

            if (elemData?.id) {
                return this.element.data.three.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        four() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.four;
            const elemConfig = this.element.config.four;

            if (elemData?.id) {
                return this.element.data.four.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        five() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.five;
            const elemConfig = this.element.config.five;

            if (elemData?.id) {
                return this.element.data.five.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        six() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.six;
            const elemConfig = this.element.config.six;

            if (elemData?.id) {
                return this.element.data.six.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        seven() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.seven;
            const elemConfig = this.element.config.seven;

            if (elemData?.id) {
                return this.element.data.seven.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        eight(){
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.eight;
            const elemConfig = this.element.config.eight;

            if (elemData?.id) {
                return this.element.data.eight.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        nine(){
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.nine;
            const elemConfig = this.element.config.nine;

            if (elemData?.id) {
                return this.element.data.nine.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        ten(){
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.nine;
            const elemConfig = this.element.config.nine;

            if (elemData?.id) {
                return this.element.data.nine.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        eleven(){
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.eleven;
            const elemConfig = this.element.config.eleven;
            if (elemData?.id) {
                return this.element.data.eleven.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },
        twelve(){
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.twelve;
            const elemConfig = this.element.config.twelve;
            if (elemData?.id) {
                return this.element.data.twelve.url;
            }
            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }
            return staticFallBackImage;
        },

        assetFilter() {
            return Filter.getByName('asset');
        },

        mediaConfigValue() {
            return this.element?.config?.sliderItems?.value;
        },
    },

    watch: {
        cmsPageState: {
            deep: true,
            handler() {
                this.$forceUpdate();
                this.updateDemoValue();
            },

        },
        'element.config.content.source': {
            handler() {
                this.updateDemoValue();
            },
        },

        mediaConfigValue(value) {
            const mediaId = this.element?.data?.media?.id;
            const isSourceStatic = this.element?.config?.media?.source === 'static';

            if (isSourceStatic && mediaId && value !== mediaId) {
                this.element.config.media.value = mediaId;
            }
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('gaisbock-image-add-more-text');
            this.initElementData('gaisbock-image-add-more-text');
        },
        updateDemoValue() {
            if (this.element.config.content.source === 'mapped') {
                this.demoValue = this.getDemoValue(this.element.config.content.value);
            }
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
        onBlurOne(content) {
            this.emitChanges(content);
        },

        onInputOne(content) {
            this.emitChangesOne(content);
        },

        emitChangesOne(content) {
            if (content !== this.element.config.subContent.value) {
                this.element.config.subContent.value = content;
                this.$emit('element-update', this.element);
            }
        },
    },
}