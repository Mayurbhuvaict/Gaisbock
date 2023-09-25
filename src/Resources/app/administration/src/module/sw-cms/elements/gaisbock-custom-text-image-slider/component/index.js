import template from './sw-cms-el-gaisbock-custom-text-image-slider.html.twig';
import './sw-cms-el-gaisbock-custom-text-image-slider.scss';

const {Mixin, Filter} = Shopware;
export default {
    template,
    inject: ['feature'],

    mixins: [
        Mixin.getByName('cms-element'),
    ],

    computed: {
        contentTitle() {
            return this.element.config.contentTitle.value;
        },

        subTitle() {
            return this.element.config.subTitle.value;
        },

        detailTitle() {
            return this.element.config.detailTitle.value;
        },
        displayImageTextModeClass() {

            if (this.element.config.TextImagePosition == null) {
                return '';
            }
            return `${this.element.config.TextImagePosition.value}`;
        },

        coverMedia() {
            const fallBackImageFileNameOne = 'framework/assets/default/cms/preview_plant_small.jpg'.slice('framework/assets/default/cms/preview_plant_small.jpg'.lastIndexOf('/') + 1);
            const staticFallBackImageOne = this.assetFilter(`administration/static/img/cms/${fallBackImageFileNameOne}`);
            const elemDataOne = this.element.config.coverUrl;

            if (elemDataOne?.value) {
                return this.element.config.coverUrl.value;
            }

            return staticFallBackImageOne;
        },

        assetFilter() {
            return Filter.getByName('asset');
        },

    },

}