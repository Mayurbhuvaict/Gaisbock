import template from './ict-newsletter-with-promotion-list.html.twig';
const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('ict-newsletter-with-promotion-list',{
    template,
    inject: [
        'repositoryFactory'
    ],
    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            popup: null,
            isLoading: true,
            sortBy: 'name',
            sortDirection: 'ASC',
            total: 0,
            searchConfigEntity: 'newsletter_popup',
        };
    },
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },
    computed: {
        popupRepository(){
            return this.repositoryFactory.create('newsletter_popup');
        },
        popupCriteria() {
            const popupCriteria = new Criteria(this.page, this.limit);

            popupCriteria.setTerm(this.term);
            popupCriteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));

            return popupCriteria;
        },
        columns() {
            return [{
                property: 'name',
                dataIndex: 'name',
                label: this.$tc('ict-newsletter-with-promotion.newsletter-popup.list.columnName'),
                routerLink: 'ict.newsletter.with.promotion.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            }, {
                property: 'visibleSettings',
                dataIndex: 'visibleSettings',
                label: this.$tc('ict-newsletter-with-promotion.newsletter-popup.list.columnVisibleSettings'),
                allowResize: true,
            },{
                property: 'isGlobal',
                dataIndex: 'isGlobal',
                label: this.$tc('ict-newsletter-with-promotion.newsletter-popup.list.columnIsGlobal'),
                allowResize: true,
            },{
                property: 'promotionActive',
                dataIndex: 'promotionActive',
                label: this.$tc('ict-newsletter-with-promotion.newsletter-popup.list.columnPromotionActiveLabel'),
                allowResize: true,
            }];
        }
    },
    created() {

        this.getList();
    },
    methods:{
        onChangeLanguage(languageId) {
            this.getList(languageId);
        },

        async getList() {
            this.isLoading = true;

            const criteria = await this.addQueryScores(this.term, this.popupCriteria);

            if (!this.entitySearchable) {
                this.isLoading = false;
                this.total = 0;

                return false;
            }

            if (this.freshSearchTerm) {
                criteria.resetSorting();
            }

            return this.popupRepository.search(criteria)
                .then(searchResult => {
                    this.popup = searchResult;
                    this.total = searchResult.total;
                    this.isLoading = false;
                });
        },

        updateTotal({ total }) {
            this.total = total;
        },
    }
});