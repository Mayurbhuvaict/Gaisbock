Shopware.Component.register('sw-cms-el-gaisbock-text-image-slider',()=>import('./component'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-text-image-slider',()=>import('./preview'));
Shopware.Component.register('sw-cms-el-config-gaisbock-text-image-slider',()=>import('./config'));

Shopware.Service('cmsService').registerCmsElement({
    name:'gaisbock-text-image-slider',
    label:'sw-cms.blocks.gaisBockTextImageSlider.label',
    component:'sw-cms-el-gaisbock-text-image-slider',
    previewComponent:'sw-cms-preview-gaisbock-text-image-slider',
    configComponent:'sw-cms-el-config-gaisbock-text-image-slider',
    defaultConfig:{
        sliderItems: {
            source: 'static',
            value: [],
            required: true,
            entity: {
                name: 'media',
            },
        },
        navigationArrows: {
            source: 'static',
            value: 'outside',
        },
        navigationDots: {
            source: 'static',
            value: null,
        },
        displayMode: {
            source: 'static',
            value: 'standard',
        },
        minHeight: {
            source: 'static',
            value: '300px',
        },
        verticalAlign: {
            source: 'static',
            value: null,
        },
        speed: {
            value: 300,
            source: 'static',
        },
        autoSlide: {
            value: false,
            source: 'static',
        },
        autoplayTimeout: {
            value: 5000,
            source: 'static',
        },
    },
    enrich: function enrich(elem, data) {
        if (Object.keys(data).length < 1) {
            return;
        }

        let entityCount = 0;
        Object.keys(elem.config).forEach((configKey) => {
            const entity = elem.config[configKey].entity;

            if (!entity) {
                return;
            }

            const entityKey = `entity-${entity.name}-${entityCount}`;
            entityCount += 1;

            if (!data[entityKey]) {
                return;
            }

            elem.data[configKey] = [];
            elem.config[configKey].value.forEach((sliderItem) => {
                elem.data[configKey].push({
                    newTab: sliderItem.newTab,
                    text:sliderItem.text,
                    url: sliderItem.url,
                    media: data[entityKey].get(sliderItem.mediaId),
                });
            });
        });
    },
});