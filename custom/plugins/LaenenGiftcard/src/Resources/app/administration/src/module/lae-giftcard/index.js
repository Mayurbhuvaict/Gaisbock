import './page/lae-giftcard-list';
import './page/lae-giftcard-detail';
import './init/service.js';

Shopware.Module.register('lae-giftcard', {
    type: 'plugin',
    name: 'Example',
    title: 'lae-giftcard.general.mainMenuItemGeneral',
    description: 'lae-giftcard.general.descriptionTextModule',
    color: '#3b82f6',
    icon: 'default-package-gift',

    routes: {
        list: {
            component: 'lae-giftcard-list',
            path: 'list'
        },
        detail: {
            component: 'lae-giftcard-detail',
            path: 'detail/:id',
            props: {
                default(route) {
                    return {
                        giftcardId: route.params.id,
                    };
                },
            },
            meta: {
                parentPath: 'lae.giftcard.list'
            }
        }
    },

    navigation: [{
        label: 'lae-giftcard.general.mainMenuItemGeneral',
        color: '#3b82f6',
        path: 'lae.giftcard.list',
        icon: 'default-package-gift',
        parent: 'sw-order',
        position: 100
    }]
});
