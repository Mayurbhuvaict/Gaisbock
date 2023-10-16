import template from './neno-marketing-essentials-newsletter-popup-list.html.twig';
import './neno-marketing-essentials-newsletter-popup-list.scss'

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('neno-marketing-essentials-newsletter-popup-list', {
    template,

    inject: [
        'repositoryFactory'
    ],

    data() {
        return {
            repository: null,
            popup: null
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
                label: this.$tc('neno-marketing-essentials.newsletter-popup.list.columnName'),
                routerLink: 'neno.marketing.essentials.overview.newsletterPopupDetail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            }, {
                property: 'visibleSettings',
                dataIndex: 'visibleSettings',
                label: this.$tc('neno-marketing-essentials.newsletter-popup.list.columnVisibleSettings'),
                allowResize: true,
            },{
                property: 'isGlobal',
                dataIndex: 'isGlobal',
                label: this.$tc('neno-marketing-essentials.newsletter-popup.list.columnIsGlobal'),
                allowResize: true,
            },{
                property: 'promotionActive',
                dataIndex: 'promotionActive',
                label: this.$tc('neno-marketing-essentials.newsletter-popup.list.columnPromotionActiveLabel'),
                allowResize: true,
            }];
        }
    },

    created() {
        this.repository = this.repositoryFactory.create('neno_marketing_essentials_newsletter_popup');

        this.repository
            .search(new Criteria(), Shopware.Context.api)
            .then((result) => {
                this.popup = result;
            });
    }
});
