import CMS from "../../../constant/sw-cms.constant";

Shopware.Component.register('sw-cms-block-gaisbock-listpage-text-image',()=>import('./component'));
Shopware.Component.register('sw-cms-preview-gaisbock-listpage-text-image',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsBlock({
    name: 'gaisbock-listpage-text-image',
    label: 'sw-cms.blocks.gaisBockListingpageHeadingimage.label',
    category: 'gaisbock-cms-elements',
    component: 'sw-cms-block-gaisbock-listpage-text-image',
    previewComponent: 'sw-cms-preview-gaisbock-listpage-text-image',
    allowedPageTypes:['product_list'],
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        gaisbockListpageImageText: {
            type: 'gaisbock-listpage-text-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'standard' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewMountain,
                        source: 'default',
                    },
                },
            },
        },
    },
});
