import CMS from "../../../constant/sw-cms.constant";

Shopware.Component.register('sw-cms-block-gaisbock-image-add-more-text',()=>import('./component'));
Shopware.Component.register('sw-cms-preview-gaisbock-image-add-more-text',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsBlock({
    name:'gaisbock-image-add-more-text',
    label:'sw-cms.blocks.gaisbockImageAddMoreText.label',
    category:'gaisbock-cms-elements',
    component:'sw-cms-block-gaisbock-image-add-more-text',
    previewComponent:'sw-cms-preview-gaisbock-image-add-more-text',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        gaisbockImageAddMoreText:{
            type: 'gaisbock-image-add-more-text',
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
    },
});