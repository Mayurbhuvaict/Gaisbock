import template from './neno-marketing-essentials-tabs-list.html.twig';
import './neno-marketing-essentials-tabs-list.scss'

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('neno-marketing-essentials-tabs-list', {
    template,

    inject: [
        'repositoryFactory'
    ],

    data() {
        return {
            repository: null,
            tab: null
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        columns() {
            return [{
                property: 'name',
                dataIndex: 'name',
                label: this.$tc('neno-marketing-essentials.tabs.list.columnName'),
                routerLink: 'neno.marketing.essentials.overview.tabsDetail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            },{
                property: 'display',
                dataIndex: 'display',
                label: this.$tc('neno-marketing-essentials.tabs.list.columnDisplay'),
                allowResize: true,
            },{
                property: 'isGlobal',
                dataIndex: 'isGlobal',
                label: this.$tc('neno-marketing-essentials.tabs.list.columnIsGlobal'),
                allowResize: true,
            }];
        }
    },

    created() {
        this.repository = this.repositoryFactory.create('neno_marketing_essentials_tabs');

        this.repository
            .search(new Criteria(), Shopware.Context.api)
            .then((result) => {
                this.tab = result;
            });
    }
});
