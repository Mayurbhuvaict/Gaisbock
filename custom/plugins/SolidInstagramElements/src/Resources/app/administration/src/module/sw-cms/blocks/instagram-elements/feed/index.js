import './component';
import './preview';

const { Service } = Shopware;

Service('cmsService').registerCmsBlock({
    name: 'solid-ie-feed',
    label: 'sw-cms.blocks.solidIE.feed.label',
    category: 'solid-instagram-elements',
    component: 'sw-cms-block-solid-ie-feed',
    previewComponent: 'sw-cms-block-preview-solid-ie-feed',
    defaultConfig: {
        marginBottom: '',
        marginTop: '',
        marginLeft: '',
        marginRight: '',
        sizingMode: 'boxed',
    },
    slots: {
        feed: {
            type: 'solid-ie-feed',
        },
    },
});
