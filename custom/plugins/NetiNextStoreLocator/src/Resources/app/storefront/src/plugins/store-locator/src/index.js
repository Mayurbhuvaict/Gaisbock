import Vue from '@vue';

import VueOffCanvas from './components/vue-plugins/offcanvas';
import VueScroll from './shared/vue-plugins/scroll';
import VueSlide from './shared/vue-plugins/slide';

import './components/util/window-neti';

import StoreComponent from './shared/vue-components/store';
import RouteComponent from './shared/vue-components/route';
import ContactFormComponent from './components/vue/contact-form';
import InfoWindowComponent from './shared/vue-components/info-window';

import IndexPage from './shared/pages/index';
import DetailPage from './shared/pages/detail';
import HttpGateway from './components/http-gateway';
import CookieConsent from './components/cookie-consent';

import { setCookieConsentClass } from './shared/google-map';

setCookieConsentClass(CookieConsent);

window.Neti = window.Neti || {};
window.Neti.StoreLocator = window.Neti.StoreLocator || {};

[
    IndexPage,
    DetailPage
].forEach(createPage => {
    const page = createPage({ HttpGateway });

    if (!document.querySelectorAll(page.el).length) {
        return;
    }

    // Make the setup method compatible with Vue 2
    if ('setup' in page) {
        const originalMountedMethod = page.mounted;

        page.mounted = async function() {
            const me   = this;
            const data = page.setup.call(me);

            for (let key in data) {
                if (data.hasOwnProperty(key)) {
                    me[key] = data[key];
                }
            }

            page.mounted = originalMountedMethod;

            return page.mounted.call(me);
        };
    }

    const componentName = page.name;

    delete page.name;

    Vue.use(VueOffCanvas);
    Vue.use(VueScroll);
    Vue.use(VueSlide);

    Vue.component('neti-store-locator-store', StoreComponent);
    Vue.component('neti-store-locator-route', RouteComponent);
    Vue.component('neti-store-locator-contact-form', ContactFormComponent);
    Vue.component('neti-store-locator-info-window', InfoWindowComponent);

    window.Neti.StoreLocator[componentName] = new Vue(page);
});