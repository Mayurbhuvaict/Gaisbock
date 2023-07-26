/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-preview-gaisbock-text', () => import('./preview'));
/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-block-gaisbock-text', () => import('./component'));

/**
 * @private
 * @package content
 */
Shopware.Service('cmsService').registerCmsBlock({
    name: 'gaisbock-text',
    label: 'sw-cms.blocks.IctText.label',
    category: 'gaisbock-cms-elements',
    component: 'sw-cms-block-gaisbock-text',
    previewComponent: 'sw-cms-preview-gaisbock-text',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        content: 'gaisbock-text',
    },
});
