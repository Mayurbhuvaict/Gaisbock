import './component/sidebar-menu';
import './component/tabs-item';

import './module/store-locator';
import './module/contact-form';
import './module/filter';

import './sw-cms/component/sw-cms-create-wizard';
import './sw-cms/component/sw-cms-list';
import './sw-cms/component/sw-cms-sidebar';
import './sw-cms/elements/sl-search';
import './sw-cms/blocks/neti-store-locator/sl-search';
import './sw-search-bar-item';
import './snippet';

const SearchTypeService              = Shopware.Service('searchTypeService');
const CustomFieldDataProviderService = Shopware.Service('customFieldDataProviderService');

SearchTypeService.upsertType('neti_store_locator', {
    entityName: 'neti_store_locator',
    placeholderSnippet: 'neti-store-locator.general.placeholderSearchBar',
    listingRoute: 'neti.store_locator.overview'
});

const entityNames = CustomFieldDataProviderService.getEntityNames();

if (false === entityNames.includes('neti_store_locator')) {
    CustomFieldDataProviderService.addEntityName('neti_store_locator');
}
