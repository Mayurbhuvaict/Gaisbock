import template from './shop-experience-detail.html.twig';
import './shop-experience-detail.scss';
const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
Component.register('hl-shop-experience-detail', {
    template,
    inject: ['repositoryFactory'],
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },
    data() {
        return {
            shopExperienceDetails: null,
            isLoading: false,
            repository: null,
            emojis: null
        };
    },
    computed: {
        shopExperienceRepository() {
            return this.repositoryFactory.create('s_plugin_hatslogic_shopping_experiences');
        },
        shopExperienceCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('customer');
            criteria.addAssociation('salesChannel');
            return criteria;
        },
        stars() {
            if (this.shopExperienceDetails.points >= 0) {
                return this.shopExperienceDetails.points;
            }
            return 0;
        },
        missingStars() {
            if (this.shopExperienceDetails.points >= 0) {
                return 5 - this.shopExperienceDetails.points;
            }
            return 5;
        }
    },
    created() {
        this.shopExperienceRepository.get(this.$route.params.id, Shopware.Context.api, this.shopExperienceCriteria).then((entity) => {
            this.shopExperienceDetails = entity;
        });
    },
    methods: {
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
