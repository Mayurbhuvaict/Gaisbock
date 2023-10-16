import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'hueb-faq-element',
    label: 'sw-cms.elements.faq.label',
    component: 'sw-cms-el-faq',
    configComponent: 'sw-cms-el-config-faq',
    previewComponent: 'sw-cms-el-preview-faq',
    defaultConfig: {
        group: {
            source: 'static',
            value: ''
        }
    }
});
