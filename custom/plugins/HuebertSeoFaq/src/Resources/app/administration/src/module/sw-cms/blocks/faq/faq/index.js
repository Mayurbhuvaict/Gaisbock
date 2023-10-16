import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'hueb-faq',
    label: 'sw-cms.blocks.faq.faq.label',
    category: 'faq',
    component: 'sw-cms-block-hueb-faq',
    previewComponent: 'sw-cms-preview-hueb-faq',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        huebFaqEl: 'hueb-faq-element'
    }
});
