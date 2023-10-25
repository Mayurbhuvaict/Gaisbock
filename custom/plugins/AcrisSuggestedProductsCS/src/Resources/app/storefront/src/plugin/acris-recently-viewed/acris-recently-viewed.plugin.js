import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';
import CookieStorage from 'src/helper/storage/cookie-storage.helper';
import ViewportDetection from 'src/helper/viewport-detection.helper';

const RECENTLY_VIEWED_LI_ID = "acris-suggested-recentlyViewed";
const RECENTLY_VIEWED_CONTENT_CONTAINER_ID = "recently-viewed-products-container";

export default class AcrisRecentlyViewedPlugin extends Plugin {

    static options = {
        id: '',
        url: '',
        recentlyViewedContainerSelector: '.recently-viewed-products-container',
        recentlyViewedItemLimit: 7,
        recentlyViewedDuration: 365,
        productSliderSelector: '[data-product-slider="true"]',
        sliderConfig: [],
        displayModeDesktop: '',
        displayModeTablet: '',
        displayModeMobile: '',

    };

    init() {
        this._client = new HttpClient(window.accessKey, window.contextToken);

        const acrisCrossSellingCookie =  CookieStorage.getItem('acris_recently_viewed');
        if (!!acrisCrossSellingCookie) {
            const splitCookie = acrisCrossSellingCookie.split("|");
            if ((splitCookie.length === 1 && splitCookie[0] === "1") || (splitCookie.length === 2 && splitCookie[0] === "1" && splitCookie[1] === this.options.id)) {
                this.addCookiesAfterRender(acrisCrossSellingCookie);
            } else if (!!this.options.id) {
                    this._client.post(this.options.url, JSON.stringify({productId: this.options.id, config: this.options.sliderConfig}), response => {
                        if (!!response) {
                            this.renderResponse(response);
                            this.showResponse();
                        }
                        this.addCookiesAfterRender(acrisCrossSellingCookie);
                    });
            }
        }
    }

    renderResponse(response) {
        const selector = document.querySelector(this.options.recentlyViewedContainerSelector);
        selector.insertAdjacentHTML("beforeend", response);

        window.PluginManager.initializePlugins();

        this.$emitter.publish('RecentlyView/afterRenderResponse', { response });
    }

    showResponse() {
        const recentlyViewedLiElement = document.getElementById(RECENTLY_VIEWED_LI_ID);
        const recentlyViewedContentElement = document.getElementById(RECENTLY_VIEWED_CONTENT_CONTAINER_ID);
        if (!!recentlyViewedLiElement && !!recentlyViewedContentElement) {
            recentlyViewedLiElement.classList.remove('d-none');
            recentlyViewedContentElement.classList.remove('d-none');
           if (this.isRecentlyViewedFirst() ||
                (ViewportDetection.getCurrentViewport() === 'XS' && this.options.displayModeMobile === 'among') ||
                (ViewportDetection.getCurrentViewport() === 'SM' && this.options.displayModeMobile === 'among') ||
                (ViewportDetection.getCurrentViewport() === 'MD' && this.options.displayModeTablet === 'among') ||
                (ViewportDetection.getCurrentViewport() === 'LG' && this.options.displayModeTablet === 'among')||
                (ViewportDetection.getCurrentViewport() === 'XL' && this.options.displayModeDesktop === 'among') ||
                (ViewportDetection.getCurrentViewport() === 'XXL' && this.options.displayModeDesktop === 'among')
            ) {
                this.activateRecentlyViewedTab();
            }
        }
    }

    rebuildSlider(){
        const slider = DomAccess.querySelector(correspondingContent, this.options.productSliderSelector, false);
    }

    initiateCookie() {
        CookieStorage.setItem('acris_recently_viewed', this.options.id, this.options.recentlyViewedDuration);
    }

    addCookiesAfterRender(acrisCrossSellingCookie) {
        const slicedCookies = this.adjustCookieToMaxLimit(acrisCrossSellingCookie); // adjusts the string size
        let cookieString = '';

        if (!slicedCookies.includes(this.options.id)) {
            cookieString = this.options.id + "|" + slicedCookies;
        } else {
            const adjustedCookies = slicedCookies.replace(this.options.id + "|", '');
            cookieString = this.options.id = this.options.id + "|" + adjustedCookies
        }

        if (!!cookieString) {
            CookieStorage.setItem('acris_recently_viewed', cookieString, this.options.recentlyViewedDuration);
        }
    }

    adjustCookieToMaxLimit(acrisCrossSellingCookie) {
        const cookieArray = acrisCrossSellingCookie.split("|");

        if (cookieArray.length >= this.options.recentlyViewedItemLimit) {
            const slicedCookieArray = cookieArray.slice(1 - this.options.recentlyViewedItemLimit);
            return slicedCookieArray.join("|");
        }

        return acrisCrossSellingCookie;
    }

    isRecentlyViewedFirst() {
        const recentlyViewedLi = document.getElementById(RECENTLY_VIEWED_LI_ID);
        return !!recentlyViewedLi && recentlyViewedLi.classList.contains('recentlyViewedIsFirst');
    }

    activateRecentlyViewedTab() {
        const recentlyViewedTabId = "cs-recentlyViewed-tab"
        const recentlyViewedTab = document.getElementById(recentlyViewedTabId);
        if (!!recentlyViewedTab) {
            recentlyViewedTab.click();
        }
    }
}
