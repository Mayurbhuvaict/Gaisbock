import './page/pluszwei-faq-manager-index';
import './page/pluszwei-faq-manager-article-create'
import './page/pluszwei-faq-manager-article-detail';
import './view/pluszwei-faq-manager-index-article';
import './view/pluszwei-faq-manager-index-category';

import './component/pluszwei-faq-manager-category-tree';
import './component/pluszwei-faq-manager-tree-item';

// sw
import './sw-category';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const {Module} = Shopware;

Module.register('pluszwei-faq-manager', {
    type: 'plugin',
    name: 'pluszwei-faq-manager',
    title: 'pluszwei-faq-manager.general.mainMenuItemGeneral',
    description: 'pluszwei-faq-manager.general.descriptionTextModule',
    color: '#8C5BE9',
    icon: 'regular-content',
    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            component: 'pluszwei-faq-manager-index',
            path: 'index',
            redirect: {
                name: 'pluszwei.faq.manager.index.article'
            },
            children: {
                article: {
                    component: 'pluszwei-faq-manager-index-article',
                    path: 'article',
                    meta: {
                        parentPath: 'pluszwei.faq.manager.index',
                    }
                },
                category: {
                    component: 'pluszwei-faq-manager-index-category',
                    path: 'category',
                    meta: {
                        parentPath: 'pluszwei.faq.manager.index',
                    }
                }
            },
        },
        'article.create': {
            components: {
                default: 'pluszwei-faq-manager-article-create'
            },
            path: 'article/new'
        },
        'article.detail': {
            components: {
                default: 'pluszwei-faq-manager-article-detail'
            },
            path: 'article/detail/:id'
        }
    },

    navigation: [{
        id: 'pluszwei-faq-manager',
        label: 'pluszwei-faq-manager.general.mainMenuItemGeneral',
        color: '#8C5BE9',
        path: 'pluszwei.faq.manager.index',
        parent: 'sw-content'
    }]
});
