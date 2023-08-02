Shopware.Component.register('sw-cms-el-gaisbock-category-image-text',()=>import('./component'));
Shopware.Component.register('sw-cms-el-config-gaisbock-category-image-text',()=>import('./config'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-category-image-text',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsElement({
    name:'gaisbock-category-image-text',
    label:'sw-cms.blocks.gaisbockCategoryImageText.label',
    component:'sw-cms-el-gaisbock-category-image-text',
    configComponent:'sw-cms-el-config-gaisbock-category-image-text',
    previewComponent:'sw-cms-el-preview-gaisbock-category-image-text',
    defaultConfig: {
        newTitle: {
            source: 'static',
            value: 'standard',
        },
        media: {
            source: 'static',
            value: null,
            required: true,
            entity: {
                name: 'media',
            },
        },
        displayMode: {
            source: 'static',
            value: 'standard',
        },
        url: {
            source: 'static',
            value: null,
        },
        newTab: {
            source: 'static',
            value: false,
        },
        minHeight: {
            source: 'static',
            value: '340px',
        },
        verticalAlign: {
            source: 'static',
            value: null,
        }
    },
    collect: Shopware.Service('cmsService').getCollectFunction(),
})