import CMS from '../../../constant/sw-cms.constant';
Shopware.Component.register('sw-cms-block-gaisbock-custom-category-extension',()=>import('./component'));
Shopware.Component.register('sw-cms-preview-gaisbock-custom-category-extension',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsBlock({
   name:'gaisbock-custom-category-extension',
   label:'sw-cms.blocks.gaisbockCustomCategoryExtension.label',
   category:'gaisbock-cms-elements',
   component:'sw-cms-block-gaisbock-custom-category-extension',
   previewComponent:'sw-cms-preview-gaisbock-custom-category-extension',
   allowedPageTypes:['product_list'],
   defaultConfig: {
      marginBottom: '20px',
      marginTop: '20px',
      marginLeft: '20px',
      marginRight: '20px',
      sizingMode: 'boxed',
   },
   slots: {
      gaisbockCustomCategoryExtension:{
          type: 'gaisbock-custom-category-extension',
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