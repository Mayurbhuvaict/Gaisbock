import './page/list';
import enGB from './snippet/en-GB.json';
import deDE from './snippet/de-DE.json';
import nlNL from './snippet/nl-NL.json';

import './page/detail';
import './view/detail-base';
import './component/general-basic-card';

import './page/create';
import './view/create-base';

Shopware.Module.register('neti-store_locator-contact_form', {
    type: 'plugin',
    name: 'NetiStoreLocator.ContactForm',

    title: 'neti-store-locator-contact-form.title',
    description: 'neti-store-locator-contact-form.description',
    icon: 'regular-database',
    entity: 'neti_store_locator-contact_form_entity',
    snippets: {
        'en-GB': enGB,
        'de-DE': deDE,
        'nl-NL': nlNL
    },
    routes: {
        overview: {
            component: 'neti-store-locator-contact-form-list',
            path: 'overview'
        },
        create: {
            component: 'neti-store-locator-contact-form-create',
            path: 'create',
            redirect: {
                name: 'neti.store_locator.contact_form.create.base'
            },
            children: {
                base: {
                    component: 'neti-store-locator-contact-form-create-base',
                    path: 'base',
                    meta: {
                        parentPath: 'neti.store_locator.contact_form.overview'
                    }
                }
            }
        },
        detail: {
            component: 'neti-store-locator-contact-form-detail',
            path: 'detail/:id',
            redirect: {
                name: 'neti.store_locator.contact_form.detail.base'
            },
            children: {
                base: {
                    component: 'neti-store-locator-contact-form-detail-base',
                    path: 'base/:edit?',
                    meta: {
                        parentPath: 'neti.store_locator.contact_form.overview'
                    }
                }
            }
        }

    }
});
