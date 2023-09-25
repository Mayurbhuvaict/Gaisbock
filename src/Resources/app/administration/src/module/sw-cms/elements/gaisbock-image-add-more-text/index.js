Shopware.Component.register('sw-cms-el-gaisbock-image-add-more-text',()=>import('./component'));
Shopware.Component.register('sw-cms-el-config-gaisbock-image-add-more-text',()=>import('./config'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-image-add-more-text',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsElement({
    name: 'gaisbock-image-add-more-text',
    label: 'sw-cms.blocks.gaisbockImageAddMoreText.label',
    component: 'sw-cms-el-gaisbock-image-add-more-text',
    configComponent: 'sw-cms-el-config-gaisbock-image-add-more-text',
    previewComponent: 'sw-cms-el-preview-gaisbock-image-add-more-text',
    defaultConfig: {
        dropdown:{
            source: 'static',
            value: null
        },
        position:{
            source: 'static',
            value: null
        },
        subTitle:{
            source: 'static',
            value: 'This is the sub'
        },
        heading:{
            source: 'static',
            value: 'This is the heading'
        },
        content: {
            source: 'static',
            value: `
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. 
                At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            `.trim(),
        },
        subHeading:{
            source: 'static',
            value: 'This is the sub heading'
        },
        subContent:{
            source: 'static',
            value: `
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. 
                At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            `.trim(),
        },
        subContentTwo:{
            source: 'static',
            value: `
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. 
                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. 
                At vero eos et accusam et justo duo dolores et ea rebum. 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            `.trim(),
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
        urlOne: {
            source: 'static',
            value: null,
        },
        urlTwo: {
            source: 'static',
            value: null,
        },
        urlThree: {
            source: 'static',
            value: null,
        },
        urlFour: {
            source: 'static',
            value: null,
        },
        urlFive: {
            source: 'static',
            value: null,
        },
        urlSix: {
            source: 'static',
            value: null,
        },
        urlSeven: {
            source: 'static',
            value: null,
        },
        urlEight: {
            source: 'static',
            value: null,
        },
        urlNine: {
            source: 'static',
            value: null,
        },
        urlTen: {
            source: 'static',
            value: null,
        },
        urlEleven: {
            source: 'static',
            value: null,
        },
        urlTwelve: {
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
        one: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        two: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        three: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        four: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        five: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        six: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        seven: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        eight: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        nine: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        ten: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        eleven: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        twelve: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        titleOne:{
            source: 'static',
            value: `Text`,
        },
        titleTwo:{
            source: 'static',
            value: 'Text',
        },
        titleThree:{
            source: 'static',
            value: 'Text',
        },
        titleFour:{
            source: 'static',
            value: 'Text',
        },
        titleFive:{
            source: 'static',
            value: 'Text',
        },
        titleSix:{
            source: 'static',
            value: 'Text',
        },
        titleSeven:{
            source: 'static',
            value: 'Text',
        },
        titleEight:{
            source: 'static',
            value: 'Text',
        },
        titleNine:{
            source: 'static',
            value: 'Text',
        },
        titleTen:{
            source: 'static',
            value: 'Text',
        },
        titleEleven:{
            source: 'static',
            value: 'Text',
        },
        titleTwelve:{
            source: 'static',
            value: 'Text',
        },
    }
});