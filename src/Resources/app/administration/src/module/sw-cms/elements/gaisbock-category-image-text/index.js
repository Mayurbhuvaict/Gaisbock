Shopware.Component.register('sw-cms-el-gaisbock-category-image-text',()=>import('./component'));
Shopware.Component.register('sw-cms-el-config-gaisbock-category-image-text',()=>import('./config'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-category-image-text',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsElement({
    name:'gaisbock-category-image-text',
    label:'sw-cms.blocks.gaisbockCategoryImageText.label',
    component:'sw-cms-el-gaisbock-category-image-text',
    configComponent:'sw-cms-el-config-gaisbock-category-image-text',
    previewComponent:'sw-cms-el-preview-gaisbock-category-image-text',
    disabledConfigInfoTextKey: 'sw-cms.elements.disabledConfigInfoTextKey',
    collect: Shopware.Service('cmsService').getCollectFunction(),
})