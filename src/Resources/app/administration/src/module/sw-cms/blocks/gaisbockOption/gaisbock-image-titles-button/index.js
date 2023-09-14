Shopware.Component.register('sw-cms-preview-gaisbock-image-titles-button', () => import('./preview'));
Shopware.Component.register('sw-cms-block-gaisbock-image-titles-button', () => import('./component'));
Shopware.Service('cmsService').registerCmsBlock({
    name: 'gaisbock-image-titles-button',
    label: 'sw-cms.blocks.gaisbockTextImageButton.gaisbockImageTitlesButtons.label',
    category: 'gaisbock-cms-elements',
    component: 'sw-cms-block-gaisbock-image-titles-button',
    previewComponent: 'sw-cms-preview-gaisbock-image-titles-button',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        gaisbockImageTitleButton: 'gaisbock-image-titles-button',
    },
});
