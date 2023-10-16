import CMS from '../../../constant/sw-cms.constant';

Shopware.Component.register('sw-cms-block-gaisbock-text-image-slider',()=>import('./component'));
Shopware.Component.register('sw-cms-preview-gaisbock-text-image-slider',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsBlock({
    name:'gaisbock-text-image-slider',
    label:'sw-cms.blocks.gaisBockTextImageSlider.label',
    category:'gaisbock-cms-elements',
    component:'sw-cms-block-gaisbock-text-image-slider',
    previewComponent:'sw-cms-preview-gaisbock-text-image-slider',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots:{
        gaisbockTextImageSlider:{
            type:'gaisbock-text-image-slider',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewCamera,
                        source: 'default',
                    },
                },
            },
        }
    }
})