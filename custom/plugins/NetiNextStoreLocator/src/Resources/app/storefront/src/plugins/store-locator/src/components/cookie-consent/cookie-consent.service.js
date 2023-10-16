import CookieStorage from 'src/helper/storage/cookie-storage.helper';

export default class CookieConsentSingleton {

    constructor(
        config
    ) {
        this.config                 = config;
        this.logEnabled             = 'development' === process.env.NODE_ENV;
        this.cookieConsentInterface = this._createCookieConsentInterface();
        this.continueBtnCallback    = null;
        this.closeButtonEnabled     = false;

        this._trackItems();
        this._registerEvents();
    }

    _createCookieConsentInterface() {
        const cookieConsentClass = window.Neti.StoreLocator.getCookieConsentInterfaceClass();

        return new cookieConsentClass();
    }

    _trackItems() {
        this.$modal = document.querySelector('.neti-store-locator-cookie-consent-modal');

        if (!this.$modal) {
            this.log('The cookie-consent.html.twig is not loaded.');
            return;
        }

        this.$continueBtn    = this.$modal.querySelector('.cookies-allowed-button');
        this.$configureBtn   = this.$modal.querySelector('.cookies-not-allowed-button');
        this.$allowedText    = this.$modal.querySelector('.cookies-allowed');
        this.$notAllowedText = this.$modal.querySelector('.cookies-not-allowed');
        this.$closeBtn       = this.$modal.querySelector('button.close');
        this.$abortBtn       = this.$modal.querySelector('button.abort-button');
        this.baseUrl         = this.$modal.getAttribute('data-url');

        // The close button is hidden by default
        this.$closeBtn.style.display = 'none';
    }

    _registerEvents() {
        if (this.$modal && this.$modal._isCookieConsentLoaded !== true) {
            this.$continueBtn.addEventListener('click', this.onContinueButtonClicked.bind(this));
            this.$configureBtn.addEventListener('click', this.onConfigureButtonClicked.bind(this));
            this.$closeBtn.addEventListener('click', this.onCloseButtonClicked.bind(this));
            this.$abortBtn.addEventListener('click', this.onAbortButtonClicked.bind(this));

            this.$modal._isCookieConsentLoaded = true;
        }

        this.cookieConsentInterface.onUpdate(this.onCookieConfigurationUpdated.bind(this));
    }

    _applyConfig(config) {
        this.closeButtonEnabled = config.closeButtonEnabled || false;
        this.abortCallback      = config.abortCallback || this.onCloseButtonClicked.bind(this);
    }

    ready(config) {
        this._applyConfig(config || {});

        return new Promise(async(resolve, reject) => {
            if (
                false === this.config._cookieConsentEnabled
                && this.cookieConsentInterface.shouldCheckIfCookieConsentIsEnabled()
            ) {
                this.log('The default cookie consent is disabled.');
                resolve();
                return;
            }

            if (!this.$modal) {
                this.log('Missing "cookie-consent.html.twig" so we continue to prevent any UI issues.');
                resolve();
                return;
            }

            if (false === this.config.requireCookieForGoogleMaps) {
                this.log('We don\'t care about any cookie acceptation.');
                resolve();
                return;
            }

            if (false === this.cookieConsentInterface.isInitialized()) {
                this.log('The cookie configurator is not initialized yet so we need for wait for it.');
                await this.cookieConsentInterface.waitForInitialization();
                this.log('The cookie configurator is now initialized.');
            }

            const cookieValue = CookieStorage.getItem('neti-store-locator-google-consent');

            if (false === cookieValue) {
                this.log('The cookie is not yet allowed by the user so he need to enable it first.');
                this.showModalWithNotAllowedCookie(resolve, reject);
                return;
            }

            if ('available' === cookieValue) {
                this.log('The cookie can be set but is not yet accepted by the user.');
                this.showModalWithAllowedCookie(resolve, reject);
                return;
            }

            if ('accepted' === cookieValue) {
                this.log('The cookie was accepted by the user, yay.');
                resolve();
                return;
            }

            this.log('Something is fucked up when we\'re here, but who cares.');
            reject();
        });
    }

    onCookieConfigurationUpdated(updatedCookies) {
        this.log('Cookies updated', updatedCookies);

        if (true === updatedCookies.detail['neti-store-locator-google-consent']) {
            this.$continueBtn.style.display    = 'block';
            this.$configureBtn.style.display   = 'none';
            this.$allowedText.style.display    = 'block';
            this.$notAllowedText.style.display = 'none';
        } else {
            this.$continueBtn.style.display    = 'none';
            this.$configureBtn.style.display   = 'block';
            this.$allowedText.style.display    = 'none';
            this.$notAllowedText.style.display = 'block';
        }
    }

    onContinueButtonClicked() {
        CookieStorage.setItem(
            'neti-store-locator-google-consent',
            'accepted',
            this.config.cookieLifetime
        );

        this.$modal.style.display = 'none';

        if (typeof this.continueBtnCallback === 'function') {
            this.continueBtnCallback();
        }
    }

    onConfigureButtonClicked() {
        this.cookieConsentInterface.open();
    }

    onCloseButtonClicked() {
        this.$modal.style.display = 'none';
    }

    onAbortButtonClicked() {
        this.abortCallback(this.baseUrl);
    }

    showModalWithNotAllowedCookie(resolve, reject) {
        this.$modal.style.display       = 'block';
        this.$continueBtn.style.display = 'none';
        this.$allowedText.style.display = 'none';
        this.continueBtnCallback        = resolve;

        if (true === this.closeButtonEnabled) {
            this.$closeBtn.style.display = 'block';
        }
    }

    showModalWithAllowedCookie(resolve, reject) {
        this.$modal.style.display          = 'block';
        this.$configureBtn.style.display   = 'none';
        this.$notAllowedText.style.display = 'none';
        this.continueBtnCallback           = resolve;

        if (true === this.closeButtonEnabled) {
            this.$closeBtn.style.display = 'block';
        }
    }

    log(...args) {
        if (!this.logEnabled) {
            return;
        }

        console.log('[cookie-consent]', ...args);
    }
}