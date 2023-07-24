Shopware.Component.register('sw-cms-el-preview-gaisbock-switch-image-text', () => import('./preview'));
Shopware.Component.register('sw-cms-el-config-gaisbock-switch-image-text', () => import('./config'));
Shopware.Component.register('sw-cms-el-gaisbock-switch-image-text', () => import('./component'));

Shopware.Service('cmsService').registerCmsElement({
    name: 'gaisbock-switch-image-text',
    label: 'sw-cms.elements.ictSwitchImageText.config.label.ictTextImage',
    component: 'sw-cms-el-gaisbock-switch-image-text',
    configComponent: 'sw-cms-el-config-gaisbock-switch-image-text',
    previewComponent: 'sw-cms-el-preview-gaisbock-switch-image-text',
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
});
