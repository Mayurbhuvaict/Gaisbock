import CMS from '../../../constant/sw-cms.constant';
Shopware.Component.register('sw-cms-preview-gaisbock-switch-image-text', () => import('./preview'));
Shopware.Component.register('sw-cms-block-gaisbock-switch-image-text', () => import('./component'));
Shopware.Service('cmsService').registerCmsBlock({
    name: 'gaisbock-switch-image-text',
    label: 'sw-cms.blocks.gaisbockTextImage.gaisbockSwitchImageTextBubble.label',
    category: 'gaisbock-cms-elements',
    component: 'sw-cms-block-gaisbock-switch-image-text',
    previewComponent: 'sw-cms-preview-gaisbock-switch-image-text',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        gaisbockSwitchImageText: {
            type: 'gaisbock-switch-image-text',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'standard' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewMountain,
                        source: 'default',
                    },
                },
            },
        },
    },
});
