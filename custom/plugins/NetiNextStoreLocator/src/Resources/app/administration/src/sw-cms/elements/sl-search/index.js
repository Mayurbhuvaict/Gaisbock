import './preview';
import './component';
import './config';

Shopware.Service('cmsService').registerCmsElement({
    name: 'sl-search',
    label: 'neti-next-store-locator.sw-cms.elements.slSearchElement.label',
    component: 'sw-cms-el-sl-search',
    configComponent: 'sw-cms-el-config-sl-search',
    previewComponent: 'sw-cms-el-preview-sl-search',
    defaultConfig: {
        searchParams: {
            source: 'static',
            value: ''
        },
    },
});
