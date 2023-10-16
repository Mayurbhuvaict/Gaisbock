import PluginOverride from '../../components/util/plugin-override';
import CookieConsentService from './cookie-consent.service';
import './cookie-consent.interface';
export const PLUGIN_CONFIGURATION_INIT = 'Neti_CookieConfiguration_Init';

 // @reference src/Storefront/Resources/app/storefront/src/plugin/cookie/cookie-configuration.plugin.js
export const COOKIE_CONFIGURATION_UPDATE = 'CookieConfiguration_Update';

PluginOverride('CookieConfiguration', {
    init: {
        after() {
            document.$emitter.publish(PLUGIN_CONFIGURATION_INIT);
        }
    }
});

function getInstance(config) {
    if (window.Neti.StoreLocator.CookieConsent) {
        return window.Neti.StoreLocator.CookieConsent;
    }

    return window.Neti.StoreLocator.CookieConsent = new CookieConsentService(config);
}

export default class CookieConsentServiceWrapper {
    constructor(config) {
        this.instance = getInstance(config);
    }

    ready(config) {
        return this.instance.ready(config);
    }
}