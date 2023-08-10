import Plugin from 'src/plugin-system/plugin.class';
import OffcanvasMenuPlugin from 'src/plugin/main-menu/offcanvas-menu.plugin';
import OffCanvas from 'src/plugin/offcanvas/offcanvas.plugin';
import LoadingIndicator from 'src/utility/loading-indicator/loading-indicator.util';
import HttpClient from 'src/service/http-client.service';
import DomAccess from 'src/helper/dom-access.helper';
import Iterator from 'src/helper/iterator.helper';
import ViewportDetection from 'src/helper/viewport-detection.helper';

/**
 * @package storefront
 */
export default class gaisbockMenuButtonClass extends OffcanvasMenuPlugin {

    init() {
        super.init();
    }

    /**
     * opens the offcanvas menu
     *
     * @param event
     * @private
     */
    _openMenu(event) {
        const isFullwidth = ViewportDetection.isXS();
        OffcanvasMenuPlugin._stopEvent(event);
        OffCanvas.open(this._content, this._registerEvents.bind(this), this.options.position, undefined, undefined, isFullwidth);
        OffCanvas.setAdditionalClassName(this.options.additionalOffcanvasClass);

        let headerMainElement = document.getElementsByClassName("gaisbock-header-main");
        headerMainElement[0].classList.add("hovered");

        this.$emitter.publish('openMenu');
    }

}
