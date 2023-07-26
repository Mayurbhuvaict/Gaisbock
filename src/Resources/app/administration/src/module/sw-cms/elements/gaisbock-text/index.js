/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-el-preview-gaisbock-text', () => import('./preview'));
/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-el-config-gaisbock-text', () => import('./config'));
/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-el-gaisbock-text', () => import('./component'));

/**
 * @private
 * @package content
 */
Shopware.Service('cmsService').registerCmsElement({
    name: 'gaisbock-text',
    label: 'sw-cms.elements.gaisbockText.label',
    component: 'sw-cms-el-gaisbock-text',
    configComponent: 'sw-cms-el-config-gaisbock-text',
    previewComponent: 'sw-cms-el-preview-gaisbock-text',
    defaultConfig: {
        content: {
            source: 'static',
            value: `
                <h2>Lorem Ipsum dolor sit amet</h2>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
            `.trim(),
        },
        verticalAlign: {
            source: 'static',
            value: null,
        },
        url: {
            source: 'static',
            value: null,
        },
    },
});
