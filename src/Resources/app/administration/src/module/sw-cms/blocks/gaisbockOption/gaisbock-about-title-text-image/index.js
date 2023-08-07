import CMS from "../../../constant/sw-cms.constant";

/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-preview-gaisbock-about-title-text-image', () => import('./preview'));

/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-block-gaisbock-about-title-text-image', () => import('./component'));

Shopware.Service('cmsService').registerCmsBlock({
    name:'gaisbock-about-title-text-image',
    label:'sw-cms.blocks.gaisbockAboutTitleTextImage.label',
    category:'gaisbock-cms-elements',
    component: 'sw-cms-block-gaisbock-about-title-text-image',
    previewComponent: 'sw-cms-preview-gaisbock-about-title-text-image',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed',
    },
    slots: {
        gaisbockAboutTitleTextImage: {
            type: 'gaisbock-about-title-text-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'standard' },
                },
                data: {
                    titleMedia: {
                        value: CMS.MEDIA.previewMountain,
                        source: 'default',
                    },
                    aboutMedia: {
                        value: CMS.MEDIA.previewMountain,
                        source: 'default',
                    }
                },
            },
        }
    },
});