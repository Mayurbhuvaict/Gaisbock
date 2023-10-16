import template from './template.html.twig';
import './style.scss';

const { Component, Data: { Criteria } } = Shopware;

Component.register('neti-store-locator-sidebar-filter', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            filterCount: 0,
            filter: {
                active: null,
                featured: null,
                showAlways: null,
                detailPageEnabled: null,
                contactFormEnabled: null,
                salesChannels: [],
                tags: [],
                street: '',
                streetNumber: '',
                zipCode: '',
                city: '',
                externalId: '',
                media: null,
                countries: []
            }
        };
    },

    computed: {
        countryRepository() {
            return this.repositoryFactory.create('country');
        },
        tagRepository() {
            return this.repositoryFactory.create('tag');
        },
        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        }
    },

    methods: {
        onResetFilters() {
            this.filter = {
                active: null,
                featured: null,
                showAlways: null,
                detailPageEnabled: null,
                contactFormEnabled: null,
                salesChannels: [],
                tags: [],
                street: '',
                streetNumber: '',
                zipCode: '',
                city: '',
                externalId: '',
                media: null,
                countries: []
            };

            this.onUpdateFilters();
        },

        onUpdateFilters() {
            const filters = [];

            if (this.filter.active !== null) {
                filters.push(Criteria.equals('active', this.filter.active === 'true'));
            }

            if (this.filter.featured !== null) {
                if (this.filter.featured === 'true') {
                    filters.push(Criteria.equals('featured', true));
                } else {
                    filters.push(
                        Criteria.multi(
                            'OR',
                            [
                                Criteria.equals('featured', false),
                                Criteria.equals('featured', null)
                            ]
                        )
                    );
                }
            }

            if (this.filter.showAlways !== null) {
                filters.push(Criteria.equals('showAlways', this.filter.showAlways));
            }

            if (this.filter.detailPageEnabled !== null) {
                if (this.filter.detailPageEnabled === 'true') {
                    filters.push(Criteria.equals('detailPageEnabled', true));
                } else {
                    filters.push(
                        Criteria.multi(
                            'OR',
                            [
                                Criteria.equals('detailPageEnabled', false),
                                Criteria.equals('detailPageEnabled', null)
                            ]
                        )
                    );
                }
            }

            if (this.filter.contactFormEnabled !== null) {
                if (this.filter.contactFormEnabled === 'true') {
                    filters.push(Criteria.equals('contactFormEnabled', true));
                } else {
                    filters.push(
                        Criteria.multi(
                            'OR',
                            [
                                Criteria.equals('contactFormEnabled', false),
                                Criteria.equals('contactFormEnabled', null)
                            ]
                        )
                    );
                }
            }

            if (this.filter.tags.length > 0) {
                filters.push(Criteria.equalsAny('tags.id', this.filter.tags));
            }

            if (this.filter.salesChannels.length > 0) {
                filters.push(Criteria.equalsAny('salesChannels.id', this.filter.salesChannels));
            }

            if (this.filter.street !== '') {
                filters.push(Criteria.contains('street', this.filter.street));
            }

            if (this.filter.streetNumber !== '') {
                filters.push(Criteria.contains('streetNumber', this.filter.streetNumber));
            }

            if (this.filter.zipCode !== '') {
                filters.push(Criteria.contains('zipCode', this.filter.zipCode));
            }

            if (this.filter.city !== '') {
                filters.push(Criteria.contains('city', this.filter.city));
            }

            if (this.filter.countries.length > 0) {
                filters.push(Criteria.equalsAny('countryId', this.filter.countries));
            }

            if (this.filter.externalId !== '') {
                filters.push(Criteria.contains('externalId', this.filter.externalId));
            }

            if (this.filter.media !== null) {
                if (this.filter.media === 'true') {
                    filters.push(
                        Criteria.multi(
                            'OR',
                            [
                                Criteria.not('OR', [Criteria.equals('pictureMediaId', null)]),
                                Criteria.not('OR', [Criteria.equals('iconMediaId', null)]),
                                Criteria.not('OR', [Criteria.equals('detailsPictureMediaId', null)]),
                            ]
                        )
                    );
                } else {
                    filters.push(
                        Criteria.multi(
                            'AND',
                            [
                                Criteria.equals('pictureMediaId', null),
                                Criteria.equals('iconMediaId', null),
                                Criteria.equals('detailsPictureMediaId', null),
                            ]
                        )
                    );
                }
            }

            this.$emit('change', filters);
            this.filterCount = filters.length;
        }
    }
});