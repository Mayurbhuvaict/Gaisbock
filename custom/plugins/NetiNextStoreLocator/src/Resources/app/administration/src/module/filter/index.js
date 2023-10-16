import './page/list';

import './component/refresh-index-modal';

import enGB from './snippet/en-GB.json';
import deDE from './snippet/de-DE.json';
import nlNL from './snippet/nl-NL.json';

Shopware.Module.register('neti-sl-filter', {
    type: 'plugin',
    title: 'neti-store-locator.filter.title',
    description: 'neti-store-locator.filter.description',
    icon: 'regular-database',
    snippets: {
        'en-GB': enGB,
        'de-DE': deDE,
        'nl-NL': nlNL
    },
    routes: {
        overview: {
            component: 'neti-sl-filter-list',
            path: 'overview'
        },
    }
});
