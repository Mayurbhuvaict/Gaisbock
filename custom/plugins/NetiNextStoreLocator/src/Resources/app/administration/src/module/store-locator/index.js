import './page/list';
import './page/detail';
import './component/form/media/neti-media-upload';
import './component/update-coordinates-modal';
import './component/map-preview-modal';
import './component/number-field';
import './component/sidebar-filter';
import './component/add-cms-modal';

import enGB from './snippet/en-GB.json';
import deDE from './snippet/de-DE.json';
import nlNL from './snippet/nl-NL.json';

Shopware.Module.register('neti-store_locator', {
    type: 'plugin',
    name: 'NetiStoreLocator',
    title: 'neti-store-locator.general.mainMenuItemGeneral',
    description: 'neti-store-locator.general.descriptionTextModule',
    icon: 'regular-database',
    entity: 'neti_store_locator',
    snippets: {
        'en-GB': enGB,
        'de-DE': deDE,
        'nl-NL': nlNL
    },
    routes: {
        overview: {
            component: 'neti-store-locator-list',
            path: 'overview'
        },
        create: {
            component: 'neti-store-locator-detail',
            path: 'create',
            redirect: {
                name: 'neti.store_locator.create.base'
            },
            children: {
                base: {
                    component: 'neti-store-locator-detail-base',
                    path: 'base',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                address: {
                    component: 'neti-store-locator-detail-address',
                    path: 'address',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                details: {
                    component: 'neti-store-locator-detail-details',
                    path: 'details',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                contact: {
                    component: 'neti-store-locator-detail-contact',
                    path: 'contact',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                business_hours: {
                    component: 'neti-store-locator-business-hours',
                    path: 'business_hours',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
            }
        },
        detail: {
            component: 'neti-store-locator-detail',
            path: 'detail/:id',
            redirect: {
                name: 'neti.store_locator.detail.base'
            },
            children: {
                base: {
                    component: 'neti-store-locator-detail-base',
                    path: 'base/:edit?',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                address: {
                    component: 'neti-store-locator-detail-address',
                    path: 'address/:edit?',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                details: {
                    component: 'neti-store-locator-detail-details',
                    path: 'details/:edit?',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                contact: {
                    component: 'neti-store-locator-detail-contact',
                    path: 'contact/:edit?',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                },
                business_hours: {
                    component: 'neti-store-locator-business-hours',
                    path: 'business_hours/:edit?',
                    meta: {
                        parentPath: 'neti.store_locator.overview'
                    }
                }
            }
        }
    },
    navigation: [
        {
            parent: 'sw-content',
            id: 'neti-store-locator',
            label: 'neti-store-locator.general.mainMenuItemGeneral',
            icon: 'regular-database',
            path: 'neti.store_locator.overview'
        }
    ],

    entityDisplayProperty: 'label',
    defaultSearchConfiguration: {
        _searchable: true,
        label: {
            _searchable: true,
            _score: 500
        }
    }
});
