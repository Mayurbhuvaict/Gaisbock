Shopware.Component.register('sw-cms-el-gaisbock-custom-category-extension',()=>import('./component'));
Shopware.Component.register('sw-cms-el-config-gaisbock-custom-category-extension',()=>import('./config'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-custom-category-extension',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsElement({
    name:'gaisbock-custom-category-extension',
    label:'sw-cms.blocks.ictCustomCategoryExtension.label',
    component:'sw-cms-el-gaisbock-custom-category-extension',
    configComponent:'sw-cms-el-config-gaisbock-custom-category-extension',
    previewComponent:'sw-cms-el-preview-gaisbock-custom-category-extension',
    disabledConfigInfoTextKey: 'sw-cms.elements.disabledConfigInfoTextKey',
    collect: Shopware.Service('cmsService').getCollectFunction(),
});