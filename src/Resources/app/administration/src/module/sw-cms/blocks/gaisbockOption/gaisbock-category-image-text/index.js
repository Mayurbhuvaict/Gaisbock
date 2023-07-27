import CMS from "../../../constant/sw-cms.constant";
Shopware.Component.register('sw-cms-block-gaisbock-category-image-text',()=>import('./component'));
Shopware.Component.register('sw-cms-preview-gaisbock-category-image-text',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsBlock({
    name:'gaisbock-category-image-text',
    label:'sw-cms.blocks.gaisbockCategoryImageText.label',
    category:'gaisbock-cms-elements',
    component:'sw-cms-block-gaisbock-category-image-text',
    previewComponent:'sw-cms-preview-gaisbock-category-image-text',
    allowedPageTypes:['product_list'],
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        gaisbockCategoryImage: {
            type: 'gaisbock-category-image-text',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.SMALL.previewCamera,
                        source: 'default',
                    },
                },
            },
        }
    }
})
