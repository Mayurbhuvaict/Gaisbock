// Core
import Plugin from 'src/plugin-system/plugin.class';
import { COOKIE_CONFIGURATION_UPDATE } from 'src/plugin/cookie/cookie-configuration.plugin';
import CookieStorageHelper from 'src/helper/storage/cookie-storage.helper';

// Services
import ApiService from './services/api.service';

// Triggers
import SearchAjaxTrigger from './triggers/search-ajax.trigger';
import SearchResultPageTrigger from './triggers/search-result-page.trigger';
import AddToCartByButtonTrigger from './triggers/add-to-cart-by-button.trigger';
import AddToCartByNumberTrigger from './triggers/add-to-cart-by-number.trigger';
import ListingViewTrigger from './triggers/listing-view.trigger';
import ViewProductTrigger from './triggers/view-product.trigger';
import CustomizeProductTrigger from './triggers/customize-product.trigger';
import AdvancedMatchingHelper from './helpers/advanced-matching.helper';
import InitiateCheckoutByCartTrigger from './triggers/initiate-checkout-by-cart.trigger';
import InitiateCheckoutByOffcanvasTrigger from './triggers/initiate-checkout-by-offcanvas.trigger';
import PageviewTrigger from './triggers/pageview.trigger';
import AddPaymentInfoByAccountTrigger from './triggers/add-payment-info-by-account.trigger';
import AddPaymentInfoByCheckoutTrigger from './triggers/add-payment-info-by-checkout.trigger';
import PurchaseTrigger from './triggers/purchase.trigger';
import CustomerEventsTrigger from './triggers/customer-events.trigger';

export default class mediameetsFacebookPixelPlugin extends Plugin {

    static options = {
        config: {
            privacyMode: 'integrate',
            pixelIds: '',
            autoConfig: true,
            advancedMatching: false,
            disablePushState: false,
            includeShippingCosts: true
        },
        shop: {
            controller: '',
            action: '',
            currency: ''
        },
        routes: {
            cart: '',
            context: '',
            order: ''
        }
    };

    init() {
        this.pixelInitialized = false;
        this.cookieName = 'mediameets-facebook-pixel-enabled';
        this.apiService = new ApiService(this.options.routes);

        const isConsentMode = this.isConsentMode();

        if (isConsentMode) {
            this._subscribeToCookieConsentManager();
        }

        if ((isConsentMode && this._consentGiven()) || this.isAlwaysActiveMode()) {
            this.start();
        }
    }

    /**
     * Starts the Facebook Pixel initialization routine:
     * - Calls API for advanced matching data (if configured)
     * - Injects and inits the Pixel
     * - Start the triggers
     */
    start() {
        // If Pixel is initialized, only start the triggers
        if (this.pixelInitialized === true) {
            this._startTriggers();
            return;
        }

        this.pixelInitialized = true;

        // If Advanced Matching is enabled, load customer data from context and init Pixel
        if (this.options.config.advancedMatching === true) {
            const me = this;

            this.apiService.loadContext(function(context){
                const customer = (context !== null && Object.prototype.hasOwnProperty.call(context, 'customer'))
                    ? AdvancedMatchingHelper.getData(context.customer) : null;

                me._initPixel(customer);
            });

            return;
        }

        // If Advanced Matching is disabled, init Pixel
        this._initPixel();
    }

    /**
     * Removes all cookies and disables all triggers.
     */
    stop() {
        this._removeCookies();
        this._disableTriggers();
    }

    /**
     * Initializes the configured Facebook Pixel id's
     */
    _initPixel(customer) {
        this._injectPixelCode();

        const config = this.options.config;

        if (! Object.prototype.hasOwnProperty.call(config, 'pixelIds') || typeof config.pixelIds !== 'string') {
            return;
        }

        const ids = config.pixelIds.split(',')
            .map(function(id){
                return id.trim();
            });

        if (ids.length === 0) {
            return;
        }

        if (config.disablePushState === true) {
            window.fbq.disablePushState = true;
        }

        ids.forEach((id) => {
            window.fbq('set', 'autoConfig', (config.autoConfig === true), id);
            window.fbq('init', id, customer);
        });

        this._startTriggers();
    }

    /**
     * Resets, registers and executes all triggers
     */
    _startTriggers() {
        this.triggers = [];
        this._registerAllTriggers();
        this._executeTriggers();
    }

    /**
     * Registers all triggers
     */
    _registerAllTriggers() {
        this._registerTrigger(PageviewTrigger);

        this._registerTrigger(AddPaymentInfoByAccountTrigger);
        this._registerTrigger(AddPaymentInfoByCheckoutTrigger);
        this._registerTrigger(AddToCartByButtonTrigger);
        this._registerTrigger(AddToCartByNumberTrigger);
        this._registerTrigger(CustomizeProductTrigger);
        this._registerTrigger(InitiateCheckoutByOffcanvasTrigger);
        this._registerTrigger(InitiateCheckoutByCartTrigger);
        this._registerTrigger(ListingViewTrigger);
        this._registerTrigger(PurchaseTrigger);
        this._registerTrigger(SearchAjaxTrigger);
        this._registerTrigger(SearchResultPageTrigger);
        this._registerTrigger(ViewProductTrigger);
        this._registerTrigger(CustomerEventsTrigger);
    }

    /**
     * Initiate and register a single trigger
     *
     * @param { BaseTrigger } trigger
     */
    _registerTrigger(trigger) {
        this.triggers.push(new trigger(this));
    }

    /**
     * Executes all registered triggers
     */
    _executeTriggers() {
        const me = this;

        this.triggers.forEach(trigger => {
            if (!trigger.supports(me.options.shop.controller, me.options.shop.action)) {
                return;
            }

            trigger.execute();
        });
    }

    /**
     * Disables all registered triggers
     */
    _disableTriggers() {
        this.triggers.forEach(trigger => {
            trigger.disable();
        });
    }

    /**
     * Subscribes to the Cookie Consent Manager Update Event
     */
    _subscribeToCookieConsentManager() {
        document.$emitter.subscribe(COOKIE_CONFIGURATION_UPDATE, this.handleCookieUpdate.bind(this));
    }

    /**
     * Handles the cookie configuration update event
     *
     * @param cookieUpdateEvent
     */
    handleCookieUpdate(cookieUpdateEvent) {
        const updatedCookies = cookieUpdateEvent.detail;

        if (!Object.prototype.hasOwnProperty.call(updatedCookies, this.cookieName)) {
            return;
        }

        if (updatedCookies[this.cookieName]) {
            this.start();
            return;
        }

        this.stop();
    }

    /**
     * Returns true if privacy mode is "use Cookie Consent Manager"
     *
     * @returns {boolean}
     */
    isConsentMode() {
        return this.options.config.privacyMode === 'integrate';
    }

    /**
     * Returns true if privacy mode is "always active"
     *
     * @returns {boolean}
     */
    isAlwaysActiveMode() {
        return this.options.config.privacyMode === 'active';
    }

    /**
     * Returns true if consent is given in consent mode
     *
     * @returns {boolean}
     */
    _consentGiven() {
        return this.isConsentMode() ? CookieStorageHelper.getItem(this.cookieName) : false;
    }

    /**
     * Removes the official Facebook Pixel cookie
     */
    _removeCookies() {
        const cookies = document.cookie.split(';');
        const fbCookieRegex = /^_fbp/;

        cookies.forEach(cookie => {
            const cookieName = cookie.split('=')[0].trim();
            if (!cookieName.match(fbCookieRegex)) {
                return;
            }

            CookieStorageHelper.removeItem(cookieName);
        });
    }

    /**
     * Injects the official Facebook Pixel JavaScript code
     */
    _injectPixelCode() {
        (function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s);
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js'));
    }
}
