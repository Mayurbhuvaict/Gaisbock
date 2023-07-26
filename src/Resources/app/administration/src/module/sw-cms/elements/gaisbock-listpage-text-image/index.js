Shopware.Component.register('sw-cms-el-gaisbock-listpage-text-image',()=>import('./component'));
Shopware.Component.register('sw-cms-el-config-gaisbock-listpage-text-image',()=>import('./config'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-listpage-text-image',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsElement({
    name:'gaisbock-listpage-text-image',
    label:'sw-cms.blocks.ictCategoryImageText.label',
    component:'sw-cms-el-gaisbock-listpage-text-image',
    configComponent:'sw-cms-el-config-gaisbock-listpage-text-image',
    previewComponent:'sw-cms-el-preview-gaisbock-listpage-text-image',
    defaultConfig:{
        heading:{
            source: 'static',
            value:'This is the Heading',
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
        },
    }
});