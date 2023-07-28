Shopware.Component.register('sw-cms-el-preview-gaisbock-image-titles-button', () => import('./preview'));
Shopware.Component.register('sw-cms-el-config-gaisbock-image-titles-button', () => import('./config'));
Shopware.Component.register('sw-cms-el-gaisbock-image-titles-button', () => import('./component'));

Shopware.Service('cmsService').registerCmsElement({
    name: 'gaisbock-image-titles-button',
    label: 'sw-cms.elements.gaisbockSwitchImageText.config.label.gaisbockTextImageButton',
    component: 'sw-cms-el-gaisbock-image-titles-button',
    configComponent: 'sw-cms-el-config-gaisbock-image-titles-button',
    previewComponent: 'sw-cms-el-preview-gaisbock-image-titles-button',
    defaultConfig: {
        position:{
            source: 'static',
            value: 'left',
        },
        title:{
            source: 'static',
            value: 'mainTitle',
        },
        media: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        mediaTwo: {
            source: 'static',
            value: null,
            required: false,
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
        urlTwo: {
            source: 'static',
            value: null,
        },
        newTab: {
            source: 'static',
            value: false,
        },
        mainTitle:{
          source: 'static',
          value: null
        },
        subTitle:{
          source: 'static',
          value: null
        },
        minHeight: {
            source: 'static',
            value: '340px',
        },
        verticalAlign: {
            source: 'static',
            value: null,
        },
        buttonOneUrl:{
            source: 'static',
            value: null,
        },
        buttonOneNewTab: {
            source:'static',
            value: null,
        },
        buttonOneText:{
            source: 'static',
            value: 'Click Here',
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
