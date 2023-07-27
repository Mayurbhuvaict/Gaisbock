Shopware.Component.register('sw-cms-el-gaisbock-title-text-image', () => import('./component'));
Shopware.Component.register('sw-cms-el-config-gaisbock-title-text-image',()=>import('./config'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-title-text-image',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsElement({
    name:'gaisbock-title-text-image',
    label:'sw-cms.blocks.gaisbockTitleTextImageMain.label',
    component:'sw-cms-el-gaisbock-title-text-image',
    configComponent:'sw-cms-el-config-gaisbock-title-text-image',
    previewComponent:'sw-cms-el-preview-gaisbock-title-text-image',
    collect: Shopware.Service('cmsService').getCollectFunction(),
    defaultConfig: {
        position:{
            source: 'static',
            value: 'left',
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
        content: {
            source: 'static',
            value: `
                <h2>Lorem Ipsum dolor sit amet</h2>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
                </p>
            `.trim(),
        },
    },
})