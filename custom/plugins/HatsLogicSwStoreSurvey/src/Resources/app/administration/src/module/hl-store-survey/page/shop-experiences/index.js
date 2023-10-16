import template from './shop-experiences.html.twig';
import './shop-experiences.scss';
const { Component } = Shopware;
const { Criteria } = Shopware.Data;
Component.register('hl-shop-experiences', {
    name: 'Shop experiences',
    template,
    inject: ['repositoryFactory'],
    data() {
        return {
            repository: null,
            experiences: null,
            isLoading: false,
            sortBy: 'createdAt',
            sortDirection: 'DESC',
        };
    },
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },
    computed: {
        shopExperienceCriteria() {
            const criteria = new Criteria(this.page, this.limit);
            //criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection));
            criteria.addAssociation('salesChannel');
            criteria.addAssociation('customer');
            return criteria;
        },
        columns() {
            return [{
                property: 'customer.firstName',
                dataIndex: 'customer.firstName,customer.lastName',
                label: this.$t('hl-store-survey.experiences.customerNameLabel'),
                routerLink: 'hl.store.survey.experience',
                allowResize: true,
                primary: true
            }, {
                property: 'salesChannel.name',
                dataIndex: 'salesChannel.name',
                label: this.$t('hl-store-survey.experiences.columnSalesChannel'),
                allowResize: true
            }, {
                property: 'comment',
                dataIndex: 'comment',
                label: this.$t('hl-store-survey.experiences.commentLabel'),
                allowResize: true
            }, {
                property: 'points',
                dataIndex: 'points',
                label: this.$t('hl-store-survey.experiences.ratingLabel'),
                allowResize: true
            }, {
                property: 'createdAt',
                dataIndex: 'createdAt',
                label: this.$t('hl-store-survey.experiences.columnCreated'),
                allowResize: true
            }];
        }
    },
    created() {
        this.isLoading = true;
        this.repository = this.repositoryFactory.create('s_plugin_hatslogic_shopping_experiences');
        this.repository.search(this.shopExperienceCriteria, Shopware.Context.api).then((result) => {
            this.experiences = result;
            this.isLoading = false;
            return result;
        }).catch(() => {
            this.isLoading = false;
        });
    },
    methods: {
        showDetail(itemId) {
            window.open('#/hl/store/survey/experience/' + itemId, '_blank');
        },
        getRatingHtml(points) {
            var emojis = { 1: "&#x1F620;", 2: "&#x1F61E;", 3: "&#x1F610;", 4: "&#x1F60A;", 5: "&#x1F60A;" };
            var ratingHtml = '<span class="emotion-container-myRating">';
            for (var key in emojis) {
                if (key <= points) {
                    ratingHtml += '<div class="emotion-style-myRating" style="opacity:1;">' + emojis[points] + '</div>';
                } else {
                    ratingHtml += '<div class="emotion-style-myRating" style="opacity: 0.3;">' + emojis[4] + '</div>';
                }
            }
            ratingHtml += '</span>';
            return ratingHtml;
        }
    }
});
