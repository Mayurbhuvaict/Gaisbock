import CMS from "../../../constant/sw-cms.constant";
/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-gaisbock-preview-title-text-image', () => import('./preview'));
/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-block-gaisbock-title-text-image', () => import('./component'));

/**
 * @private
 * @package content
 */
Shopware.Service('cmsService').registerCmsBlock({
   name: 'gaisbock-title-text-image',
   label: 'sw-cms.blocks.gaisbockTitleTextImage.label',
   category: 'gaisbock-cms-elements',
   component: 'sw-cms-block-gaisbock-title-text-image',
   previewComponent: 'sw-cms-gaisbock-preview-title-text-image',
   defaultConfig: {
      marginBottom: '20px',
      marginTop: '10px',
      marginLeft: '10px',
      marginRight: '10px',
      sizingMode: 'boxed',
   },
   slots: {
      gaisbockTitleTextImage: {
         type: 'gaisbock-title-text-image',
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