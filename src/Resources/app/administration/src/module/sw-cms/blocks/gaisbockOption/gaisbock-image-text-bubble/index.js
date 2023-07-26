import CMS from '../../../constant/sw-cms.constant';
/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-preview-gaisbock-image-text-bubble', () => import('./preview'));
/**
 * @private
 * @package content
 */
Shopware.Component.register('sw-cms-block-gaisbock-image-text-bubble', () => import('./component'));

/**
 * @private
 * @package content
 */
Shopware.Service('cmsService').registerCmsBlock({
    name: 'gaisbock-image-text-bubble',
    label: 'sw-cms.blocks.ictImageText.label',
    category: 'gaisbock-cms-elements',
    component: 'sw-cms-block-gaisbock-image-text-bubble',
    previewComponent: 'sw-cms-preview-gaisbock-image-text-bubble',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '10px',
        marginLeft: '10px',
        marginRight: '10px',
        sizingMode: 'boxed',
    },
    slots: {
        'lef-text': {
            type: 'gaisbock-text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Steh zu Deiner Natur:Pflegeprodukte fur Manner</h2>
                        `.trim(),
                    },
                },
            },
        },
        'left-image': {
            type: 'gaisbock-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                    minHeight: { source: 'static', value: '130px' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewCamera,
                        source: 'default',
                    },
                },
            },
        },
        'left-text': {
            type: 'gaisbock-text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
                        `.trim(),
                    },
                },
            },
        },


        'left1-image': {
            type: 'gaisbock-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                    minHeight: { source: 'static', value: '130px' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewCamera,
                        source: 'default',
                    },
                },
            },
        },
        'left1-text': {
            type: 'gaisbock-text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
                        `.trim(),
                    },
                },
            },
        },


        'center-image': {
            type: 'gaisbock-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                    minHeight: { source: 'static', value: '130px' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewPlant,
                        source: 'default',
                    },
                },
            },
        },
        'center-text': {
            type: 'gaisbock-text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
                        `.trim(),
                    },
                },
            },
        },
        'center1-image': {
            type: 'gaisbock-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                    minHeight: { source: 'static', value: '130px' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewPlant,
                        source: 'default',
                    },
                },
            },
        },
        'center1-text': {
            type: 'gaisbock-text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
                        `.trim(),
                    },
                },
            },
        },

        'right-image': {
            type: 'gaisbock-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                    minHeight: { source: 'static', value: '130px' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewGlasses,
                        source: 'default',
                    },
                },
            },
        },
        'right-text': {
            type: 'gaisbock-text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
                        `.trim(),
                    },
                },
            },
        },
        'right1-image': {
            type: 'gaisbock-image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' },
                    minHeight: { source: 'static', value: '130px' },
                },
                data: {
                    media: {
                        value: CMS.MEDIA.previewGlasses,
                        source: 'default',
                    },
                },
            },
        },
        'right1-text': {
            type: 'gaisbock-text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h2 style="text-align: center;">Lorem Ipsum dolor</h2>
                        `.trim(),
                    },
                },
            },
        },
    },
});
