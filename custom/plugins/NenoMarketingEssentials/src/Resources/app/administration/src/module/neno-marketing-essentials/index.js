import './page/neno-marketing-essentials-overview';
import './view/neno-marketing-essentials-newsletter-popup/neno-marketing-essentials-newsletter-popup-list';
import './view/neno-marketing-essentials-newsletter-popup/neno-marketing-essentials-newsletter-popup-create';
import './view/neno-marketing-essentials-newsletter-popup/neno-marketing-essentials-newsletter-popup-detail';
import './view/neno-marketing-essentials-register-popup/neno-marketing-essentials-register-popup-list';
import './view/neno-marketing-essentials-register-popup/neno-marketing-essentials-register-popup-create';
import './view/neno-marketing-essentials-register-popup/neno-marketing-essentials-register-popup-detail';
import './view/neno-marketing-essentials-tabs/neno-marketing-essentials-tabs-list';
import './view/neno-marketing-essentials-tabs/neno-marketing-essentials-tabs-create';
import './view/neno-marketing-essentials-tabs/neno-marketing-essentials-tabs-detail';
import './view/neno-marketing-essentials-conversion-bar/neno-marketing-essentials-conversion-bar-detail';
import './view/neno-marketing-essentials-free-shipping-bar/neno-marketing-essentials-free-shipping-bar-detail';

import deDE from './snippet/de-DE';
import enGB from './snippet/en-GB';

const MODULE_TITLE = 'neno-marketing-essentials.general.menuTitle';

Shopware.Module.register('neno-marketing-essentials', {
    type: 'plugin',
    name: 'neno-marketing-essentials',
    title: MODULE_TITLE,
    description: 'Marketing Essentials',
    color: '#d1d9e0',
    icon: 'default-object-lab-flask',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB,
    },

    routes: {
        overview: {
            component: 'neno-marketing-essentials-overview',
            path: 'overview',

            children: {
                newsletterPopupList: {
                    component: 'neno-marketing-essentials-newsletter-popup-list',
                    path: 'newsletter-popup-list'
                },

                newsletterPopupDetail: {
                    component: 'neno-marketing-essentials-newsletter-popup-detail',
                    path: 'newsletter-popup-detail/:id',
                },

                newsletterPopupCreate: {
                    component: 'neno-marketing-essentials-newsletter-popup-create',
                    path: 'newsletter-popup-create'
                },

                registerPopupList: {
                    component: 'neno-marketing-essentials-register-popup-list',
                    path: 'register-popup-list'
                },

                registerPopupDetail: {
                    component: 'neno-marketing-essentials-register-popup-detail',
                    path: 'register-popup-detail/:id'
                },

                registerPopupCreate: {
                    component: 'neno-marketing-essentials-register-popup-create',
                    path: 'register-popup-create'
                },

                tabsList: {
                    component: 'neno-marketing-essentials-tabs-list',
                    path: 'tabs-list'
                },

                tabsDetail: {
                    component: 'neno-marketing-essentials-tabs-detail',
                    path: 'tab/:id'
                },

                tabsCreate: {
                    component: 'neno-marketing-essentials-tabs-create',
                    path: 'tabs-create'
                },

                conversionBarDetail: {
                    component: 'neno-marketing-essentials-conversion-bar-detail',
                    path: 'conversion-bar-detail'
                },

                freeShippingBarDetail: {
                    component: 'neno-marketing-essentials-free-shipping-bar-detail',
                    path: 'free-shipping-bar-detail'
                }
            }
        },
    },

    navigation: [{
        parent: 'sw-marketing',
        label: MODULE_TITLE,
        color: '#0013ff',
        path: 'neno.marketing.essentials.overview',
        icon: 'default-object-lab-flask'
    }]
});
