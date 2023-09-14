Shopware.Component.register('sw-cms-preview-gaisbock-custom-text-on-image',()=>import('./preview'));
Shopware.Component.register('sw-cms-block-gaisbock-custom-text-on-image',()=>import('./component'));

Shopware.Service('cmsService').registerCmsBlock({
    name:'gaisbock-custom-text-on-image',
    label:'sw-cms.blocks.gaisbockCustomTextOnImage.label',
    category:'gaisbock-cms-elements',
    component: 'sw-cms-block-gaisbock-custom-text-on-image',
    previewComponent: 'sw-cms-preview-gaisbock-custom-text-on-image',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
        backgroundMedia: {
            url: '/administration/static/img/cms/preview_mountain_large.jpg',
        },
    },
    slots: {
        gaisbockTextOnImage: 'gaisbock-custom-text-on-image',
    },
});