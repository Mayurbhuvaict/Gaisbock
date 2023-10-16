import './page/ict-newsletter-with-promotion-detail';
import './page/ict-newsletter-with-promotion-list';

const { Module } = Shopware;

Module.register('ict-newsletter-with-promotion',{
    type: 'plugin',
    name: 'ict-newsletter-with-promotion',
    title: 'ict-newsletter-with-promotion.general.menuTitle',
    description: 'Newsletter Popups',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#FFD700',
    icon: 'regular-megaphone',
    favicon: 'icon-module-products.png',
    entity: 'newsletter_popup',
    routes: {
        index:{
            components: {
                default: 'ict-newsletter-with-promotion-list',
            },
            path: 'index',
        },
        create: {
            component: 'ict-newsletter-with-promotion-detail',
            path: 'create',
            meta: {
                parentPath: 'ict.newsletter.with.promotion.index',
            },
        },
        detail: {
            component: 'ict-newsletter-with-promotion-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'ict.newsletter.with.promotion.index',
            },
            props: {
                default(route) {
                    return {
                        popupId: route.params.id,
                    };
                },
            },
        },
    },
    navigation: [{
        id: 'ict-newsletter-popup',
        icon: 'regular-megaphone',
        color: '#FFD700',
        path: 'ict.newsletter.with.promotion.index',
        parent: 'sw-marketing',
        label: 'ict-newsletter-with-promotion.general.menuTitle'
    }],

});