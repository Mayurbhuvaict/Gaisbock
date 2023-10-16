import AjaxOffCanvas from 'src/plugin/offcanvas/ajax-offcanvas.plugin';
import ViewportDetection from 'src/helper/viewport-detection.helper';

const OffCanvasCartPlugin = PluginManager.getPlugin('OffCanvasCart').get('class');

/**
 * @package checkout
 */
export default class OffCanvasCartPluginOverride extends OffCanvasCartPlugin {
    /**
     * public method to open the offCanvas
     *
     * @param {string} url
     * @param {{}|FormData} data
     * @param {function|null} callback
     */
    openOffCanvas(url, data, callback) {
        const isFullwidth = ViewportDetection.isXS();
        AjaxOffCanvas.close();
        AjaxOffCanvas.open(url, data, this._onOffCanvasOpened.bind(this, callback), this.options.offcanvasPosition, true, undefined, isFullwidth);
        AjaxOffCanvas.setAdditionalClassName(this.options.additionalOffcanvasClass);
        window.PluginManager.initializePlugins();
    }
}
