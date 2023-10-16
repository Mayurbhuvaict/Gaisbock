import './page/shop-experiences';
import './page/shop-experience';
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';
Shopware.Module.register('hl-store-survey', {
    name: 'HatsLogicSwStoreSurvey',
    color: '#ff3d58',
    icon: 'default-chart-sales',
    title: 'hl-store-survey.titles.pluginName',
    description: 'hl-store-survey.contents.menuDescription',
    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },
    routes: {
        experiences: {
            component: 'hl-shop-experiences',
            path: 'experiences'
        },
        experience: {
            component: 'hl-shop-experience-detail',
            path: 'experience/:id',
            meta: {
                parentPath: 'hl.store.survey.experiences'
            }
        }
    },
    navigation: [{
        id: 'hl-store-survey-shop-experiences',
        path: 'hl.store.survey.experiences',
        label: 'hl-store-survey.experiences.menuLabel',
        parent: 'sw-marketing',
    }]
});
