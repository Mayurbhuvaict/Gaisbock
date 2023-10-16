import CMS from "../../../constant/sw-cms.constant";
Shopware.Component.register('sw-cms-block-gaisbock-custom-text-image-slider',()=>import('./component'));
Shopware.Component.register('sw-cms-preview-gaisbock-custom-text-image-slider',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsBlock({
    name:'gaisbock-custom-text-image-slider',
    label:'Custom Text Image Slider',
    category:'gaisbock-cms-elements',
    component:'sw-cms-block-gaisbock-custom-text-image-slider',
    previewComponent:'sw-cms-preview-gaisbock-custom-text-image-slider',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        'gaisbockCustomTextImageSlider': {
            type: 'gaisbock-custom-text-image-slider',
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
