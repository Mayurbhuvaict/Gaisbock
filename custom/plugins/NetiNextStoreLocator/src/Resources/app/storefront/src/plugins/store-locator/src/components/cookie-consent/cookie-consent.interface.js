import { COOKIE_CONFIGURATION_UPDATE, PLUGIN_CONFIGURATION_INIT } from './index';

/**
 * This interface is used to connect the cookie consent singleton with the cookie configurator plugin.
 */
class CookieConsentInterface {

    constructor() {
        this._initialized = this.getPluginInstance() !== null;

        this._initCallback   = null;
        this._updateCallback = null;

        this.registerEvents();
    }

    _onInit() {
        this._initialized = true;

        if (typeof this._initCallback === 'function') {
            this._initCallback();
            this._initCallback = null;
        }
    }

    _onCookieConfigurationUpdated(updatedCookies) {
        if (typeof this._updateCallback === 'function') {
            this._updateCallback(updatedCookies);
        }
    }

    registerEvents() {
        document.$emitter.subscribe(PLUGIN_CONFIGURATION_INIT, this._onInit.bind(this));
        document.$emitter.subscribe(COOKIE_CONFIGURATION_UPDATE, this._onCookieConfigurationUpdated.bind(this));
    }

    isInitialized() {
        return this._initialized;
    }

    /**
     * This method is called when the cookie configurator settings should be opened.
     */
    open() {
        const plugin = this.getPluginInstance();

        if (plugin) {
            plugin.openOffCanvas();
        }
    }

    /**
     * This method register the callback that is being called when the cookie configurator settings are updated.
     *
     * @param callback
     */
    onUpdate(callback) {
        this._updateCallback = callback;
    }

    /**
     * This method is called internally to wait for the initialization of the cookie consent plugin.
     * Yet is listens on the event PLUGIN_CONFIGURATION_INIT.
     *
     * @returns {Promise<unknown>}
     */
    waitForInitialization() {
        return new Promise((resolve, reject) => {
            if (this._initialized) {
                resolve();
                return;
            }

            this._initCallback = resolve;
        });
    }

    /**
     * This method should return the current plugin instance of the cookie configurator.
     *
     * @returns {*|null}
     */
    getPluginInstance() {
        const pluginList = window.PluginManager.getPluginList();

        if ('CookieConfiguration' in pluginList) {
            return window.PluginManager.getPluginInstances('CookieConfiguration')[0] || null;
        }

        return null;
    }

    /**
     * This method should be overwritten if you don't want us to check if the default cookie consent is enabled.
     *
     * @returns {boolean}
     */
    shouldCheckIfCookieConsentIsEnabled() {
        return true;
    }

}

window.Neti = window.Neti || {};
window.Neti.StoreLocator = window.Neti.StoreLocator || {};

window.Neti.StoreLocator.CookieConsentInterfaceClass = CookieConsentInterface;
window.Neti.StoreLocator.getCookieConsentInterfaceClass = function() {
    return window.Neti.StoreLocator.CookieConsentInterfaceClass;
};