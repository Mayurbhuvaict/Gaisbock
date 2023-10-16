import template from './template.twig';
import './style.scss';

const { Component, Mixin }  = Shopware;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('neti-store-locator-address-basic-card', {
    template,

    mixins: [
        Mixin.getByName('notification'),
    ],

    data() {
        return {
            isLocating: false,
            isLocatingSuccessful: false,
        };
    },

    props: {
        store: {
            type: Object,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        },
        countries: {
            type: Array,
            required: true,
            default() {
                return [];
            }
        }
    },

    computed: {
        ...mapPropertyErrors('store', ['street', 'streetNumber', 'zipCode', 'city', 'countryId']),

        selectedCountry() {
            if (typeof this.countries.get === 'function') {
                return this.countries.get(this.store.countryId) || null;
            }

            return null;
        }
    },

    methods: {
        onLocateAddress() {
            let httpClient = Shopware.Application.getContainer('init').httpClient;
            let headers    = {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${ Shopware.Context.api.authToken.access }`,
                'Content-Type': 'application/json'
            };

            this.isLocating           = true;
            this.isLocatingSuccessful = false;

            httpClient.post('_action/neti-store-locator/locate', this.store, { headers }).then(({ data: response }) => {
                this.isLocating = false;

                if (response.success) {
                    this.store.longitude = response.data.longitude;
                    this.store.latitude  = response.data.latitude;

                    this.isLocatingSuccessful = true;
                } else {
                    this.createNotificationError({
                        title: this.$t('neti-store-locator.detail.titleLocateError'),
                        message: this.$t('neti-store-locator.detail.messageLocateError')
                    });
                }
            }).catch(error => {
                this.isLocating = false;

                this.createNotificationError({
                    title: this.$t('neti-store-locator.detail.titleLocateError'),
                    message: this.$t('neti-store-locator.detail.messageLocateError')
                });
            })
        }
    }
});
