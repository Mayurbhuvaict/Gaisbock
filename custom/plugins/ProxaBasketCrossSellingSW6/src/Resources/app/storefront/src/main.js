// Import all necessary Storefront plugins and scss files
import ProductSliderPlugin from './plugin/slider/product-slider.plugin';
import OffCanvasCartPluginOverride from "./plugin/offcanvas-cart/offcanvas-cart.plugin";

// Override them via the existing PluginManager
PluginManager.override('ProductSlider', ProductSliderPlugin, '[data-product-slider]');
PluginManager.override('OffCanvasCart', OffCanvasCartPluginOverride, '[data-offcanvas-cart]');

// Necessary for the webpack hot module reloading server
if (module.hot) {
    module.hot.accept();
}