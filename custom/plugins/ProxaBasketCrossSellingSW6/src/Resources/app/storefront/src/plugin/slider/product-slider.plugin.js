import deepmerge from 'deepmerge';
import BaseSliderPlugin from 'src/plugin/slider/base-slider.plugin';
import DomAccess from 'src/helper/dom-access.helper';
import ViewportDetection from 'src/helper/viewport-detection.helper';

const BaseProductSliderPlugin = PluginManager.getPlugin('ProductSlider').get('class');

export default class ProductSliderPlugin extends BaseProductSliderPlugin {

    /**
     * default slider options
     *
     * @type {*}
     */
    static options = deepmerge(BaseSliderPlugin.options, {
        containerSelector: '[data-product-slider-container=true]',
        controlsSelector: '[data-product-slider-controls=true]',
        productboxMinWidth: '300px',
        productboxMinHeight: '300px'
    });

    /**
     * register all needed events
     *
     * @private
     */
    _registerEvents() {

        super._registerEvents();
        if (this.options.slider.axis === "vertical") {
            if (this._slider) {
                window.addEventListener('resize', () => this.rebuild(ViewportDetection.getCurrentViewport()));
                this.rebuild(ViewportDetection.getCurrentViewport());
            }
        }
    }

    /**
     * extends the slider settings with the slider item limit depending on the product-box and the container width
     *
     * @private
     */
    _addItemLimit() {

        const gutter = this._sliderSettings.gutter;
        let itemLimit;
        if (this.options.slider.axis === "vertical") {
            const containerHeight = this._getInnerHeight();
            const itemHeight = parseInt(this.options.productboxMinHeight.replace('px', ''), 0);
            itemLimit = Math.ceil(containerHeight / (itemHeight + gutter));
        } else {
            const containerWidth = this._getInnerWidth();
            const itemWidth = parseInt(this.options.productboxMinWidth.replace('px', ''), 0);
            itemLimit = Math.floor(containerWidth / (itemWidth + gutter));
        }

        this._sliderSettings.items = Math.max(1, itemLimit);
    }

    _getInnerHeight() {

        const computedStyle = getComputedStyle(this.el);
        const element = DomAccess.querySelectorAll(this.el, ".product-slider-inner", false)[0]
        const elementTitleHeight = DomAccess.querySelectorAll(this.el, ".cms-element-title", false)[0].clientHeight

        if (!computedStyle) return;

        // height with padding
        let height = element.clientHeight ;
        element.style.height = "calc(100% - " + elementTitleHeight + "px)"
        height -= parseFloat(computedStyle.paddingTop) + parseFloat(computedStyle.paddingBottom);

        return height;
    }
}
