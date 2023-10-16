import './component';
import './config';
import './preview';

const { Service } = Shopware;

Service('cmsService').registerCmsElement({
    name: 'solid-ie-feed',
    label: 'sw-cms.elements.solidIE.feed.label',
    component: 'sw-cms-el-solid-ie-feed',
    configComponent: 'sw-cms-el-config-solid-ie-feed',
    previewComponent: 'sw-cms-el-preview-solid-ie-feed',
    defaultConfig: {
        layout: {
            source: 'static',
            value: 'grid',
        },
        maxPostCount: {
            source: 'static',
            value: '9',
        },
        gutter: {
            source: 'static',
            value: 'default',
        },
        gutterWidthValue: {
            source: 'static',
            value: '',
        },
        gutterWidthUnit: {
            source: 'static',
            value: 'px',
        },
        theme: {
            source: 'static',
            value: 'simple',
        },
    },
});
