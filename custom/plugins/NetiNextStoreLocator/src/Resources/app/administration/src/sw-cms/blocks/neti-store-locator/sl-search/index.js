import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'sl-search',
    category: 'neti-store-locator',
    label: 'neti-next-store-locator.sw-cms.elements.slSearchElement.block.blockLabel',
    component: 'sw-cms-block-sl-search',
    previewComponent: 'sw-cms-preview-sl-search',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'sl-search'
    }
});
