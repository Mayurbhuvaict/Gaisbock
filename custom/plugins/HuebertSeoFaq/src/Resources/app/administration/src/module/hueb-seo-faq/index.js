import './page/hueb-seo-faq-listing';

//import './component/huebert-faq-icon';

import deDE from "./snippet/de-DE";
import enGB from "./snippet/en-GB";

const { Module } = Shopware;

Module.register('hueb-seo-faq',{
    type: 'plugin',
    name: 'HuebSeoFaq',
    title: 'hueb-seo-faq.page.label',
    description: 'hueb-seo-faq.page.description',
    color: '#0a00ff',
    icon: 'regular-lightbulb',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        listing: {
            component: 'hueb-seo-faq-listing',
            path: 'listing',
            meta: {
                parentPath: 'sw.settings.index'
            }
        }
    },

    settingsItem: [{
        name: 'hueb-seo-faq-listing',
        group: 'plugins',
        to: 'hueb.seo.faq.listing',
        label: 'hueb-seo-faq.page.label',
        backgroundEnabled: false,
        icon: 'regular-search'
    }]
});
