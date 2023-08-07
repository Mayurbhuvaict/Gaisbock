import template from './sw-cms-el-gaisbock-about-title-text-image.html.twig';
import './sw-cms-el-giasbock-about-title-text-image.scss';
import CMS from "../../../constant/sw-cms.constant";

const { Mixin, Filter } = Shopware;
export default {
    template,
    mixins: [
        Mixin.getByName('cms-element'),
    ],
    computed: {
        titleText() {
            return this.element.config.title.value;
        },
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
                this.element.config.minHeight.value !== 0 ? this.element.config.minHeight.value : '340px',
            };
        },
        position() {
            if (!this.element?.data?.position) {
                return {
                    position: 'left',
                };
            }
            return this.element.data.position;
        },
        imgStyles() {
            return {
                'align-self': this.element.config.verticalAlign.value || null,
            };
        },
        titleMediaUrl() {
            const fallBackImageFileName = CMS.MEDIA.previewGlasses.slice(CMS.MEDIA.previewGlasses.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.titleMedia;
            const elemConfig = this.element.config.titleMedia;

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
                return this.element.data.titleMedia.url;
            }

            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }

            return staticFallBackImage;
        },

        aboutMediaUrl() {
            const fallBackImageFileName = CMS.MEDIA.previewMountain.slice(CMS.MEDIA.previewMountain.lastIndexOf('/') + 1);
            const staticFallBackImage = this.assetFilter(`administration/static/img/cms/${fallBackImageFileName}`);
            const elemData = this.element.data.aboutMedia;
            const elemConfig = this.element.config.aboutMedia;

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
                return this.element.data.aboutMedia.url;
            }

            if (elemData?.url) {
                return this.assetFilter(elemConfig.url);
            }

            return staticFallBackImage;
        },
        assetFilter() {
            return Filter.getByName('asset');
        },
        titleMediaConfigValue() {
            return this.element?.config?.sliderItems?.value;
        },

        aboutMediaConfigValue() {
            return this.element?.config?.sliderItems?.value;
        },
    },
    data() {
        return {
            editable: true,
            demoValue: '',
        };
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
        titleMediaConfigValue(value) {
            const mediaId = this.element?.data?.titleMedia?.id;
            const isSourceStatic = this.element?.config?.titleMedia?.source === 'static';

            if (isSourceStatic && mediaId && value !== mediaId) {
                this.element.config.titleMedia.value = mediaId;
            }
        },
        aboutMediaConfigValue(value){
            const mediaId = this.element?.data?.aboutMedia?.id;
            const isSourceStatic = this.element?.config?.aboutMedia?.source === 'static';

            if (isSourceStatic && mediaId && value !== mediaId) {
                this.element.config.aboutMedia.value = mediaId;
            }
        }
    },

    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('gaisbock-about-title-text-image');
            this.initElementData('gaisbock-about-title-text-image');
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
    },
}