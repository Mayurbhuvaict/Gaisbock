Shopware.Component.register('sw-cms-el-gaisbock-custom-text-image-slider',()=>import('./component'));
Shopware.Component.register('sw-cms-el-config-gaisbock-custom-text-image-slider',()=>import('./config'));
Shopware.Component.register('sw-cms-el-preview-gaisbock-custom-text-image-slider',()=>import('./preview'));

Shopware.Service('cmsService').registerCmsElement({
    name:'gaisbock-custom-text-image-slider',
    label:'sw-cms.blocks.gaisbockCategoryImageText.label',
    component:'sw-cms-el-gaisbock-custom-text-image-slider',
    configComponent:'sw-cms-el-config-gaisbock-custom-text-image-slider',
    previewComponent:'sw-cms-el-preview-gaisbock-custom-text-image-slider',
    defaultConfig:{
        coverMedia: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        coverUrl: {
            source: 'static',
            value: null,
        },
        sliderItems: {
            source: 'static',
            value: [],
            required: true,
            entity: {
                name: 'media',
            },
        },
        displayMode: {
            source: 'static',
            value: 'standard',
        },
        TextImagePosition: {
            source: 'static',
            value: 'TextBeforeImage',
        },
        contentTitle: {
            source: 'static',
            value: 'Lorem ipsum dolor'
        },
        subTitle: {
            source: 'static',
            value: 'Lorem ipsum dolor sit amet'
        },
        detailTitle: {
            source: 'static',
            value: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut\n' +
                '                    labore et dolore magna aliqua.'
        },
        hideContent:{
            source: 'static',
            value: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut\n' +
                '                    labore et dolore magna aliqua.'
        },
        afterSliderText:{
            source: 'static',
            value: 'Lorem ipsum dolor sit amet'
        }

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
})