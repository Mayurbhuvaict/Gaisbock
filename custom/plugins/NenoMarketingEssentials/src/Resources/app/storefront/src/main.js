import NewsletterPopup from './newsletter-popup/newsletter-popup.plugin';
import NewsletterPopupForm from './newsletter-popup/newsletter-popup-form.plugin';
import RegisterPopup from './register-popup/register-popup.plugin';
import ConversionBar from './conversion-bar/conversion-bar.plugin';
import FreeShippingBar from "./free-shipping-bar/free-shipping-bar";

const PluginManager = window.PluginManager;
PluginManager.register('NewsletterPopup', NewsletterPopup, '[data-newsletter-popup]');
PluginManager.register('NewsletterPopupForm', NewsletterPopupForm, '[data-newsletter-popup-form]');
PluginManager.register('RegisterPopup', RegisterPopup, '[data-register-popup]');
PluginManager.register('ConversionBar', ConversionBar, '[data-conversion-bar]');
PluginManager.register('FreeShippingBar', FreeShippingBar, '[data-free-shipping-bar]');
