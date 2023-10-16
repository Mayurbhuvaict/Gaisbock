import template from './neno-marketing-essentials-free-shipping-bar-detail.html.twig';
import './neno-marketing-essentials-free-shipping-bar-detail.scss'

const { Component, Mixin } = Shopware;
const DOMAIN = 'NenoMarketingEssentials.config';

Component.register('neno-marketing-essentials-free-shipping-bar-detail', {
    template,

    inject: [
        'systemConfigApiService'
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    props: {
        salesChannelId: {
            required: false,
            type: String,
            default: null
        }
    },

    data() {
        return {
            currentSalesChannelId: this.salesChannelId,
            config: {},
            actualConfigData: {},
            conversionBar: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    created() {
        this.createdComponent();
    },

    methods: {

        createdComponent() {
            this.isLoading = true;
            this.readConfig()
                .then(() => {
                    this.readAll().then(() => {
                        this.isLoading = false;
                    });
                })
                .catch(({ response: { data } }) => {
                    if (data && data.errors) {
                        this.createErrorNotification(data.errors);
                    }
                });
        },

        readConfig() {
            return this.systemConfigApiService
                .getConfig(DOMAIN)
                .then(data => {
                    this.config = data;
                });
        },

        readAll() {
            this.isLoading = true;

            // Return when data for this salesChannel was already loaded
            if (this.actualConfigData.hasOwnProperty(this.currentSalesChannelId)) {
                this.isLoading = false;
                return Promise.resolve();
            }

            return this.loadCurrentSalesChannelConfig();
        },

        loadCurrentSalesChannelConfig() {
            this.isLoading = true;

            return this.systemConfigApiService.getValues(DOMAIN, this.currentSalesChannelId)
                .then(values => {
                    console.log(values);
                    this.$set(this.actualConfigData, this.currentSalesChannelId, values);
                    console.log({ ref: this.actualConfigData });
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        saveAll() {
            this.isLoading = true;
            return this.systemConfigApiService
                .batchSave(this.actualConfigData)
                .finally(() => {
                    this.isLoading = false;
                });
        },

        createErrorNotification(errors) {
            let message = `<div>${this.$tc(
                'sw-config-form-renderer.configLoadErrorMessage',
                errors.length
            )}</div><ul>`;

            errors.forEach((error) => {
                message = `${message}<li>${error.detail}</li>`;
            });
            message += '</ul>';

            this.createNotificationError({
                title: this.$tc('global.default.error'),
                message: message,
                autoClose: false
            });
        },
        onSalesChannelChanged(salesChannelId) {
            this.currentSalesChannelId = salesChannelId;
            this.readAll();
        },

        onClickSave() {
            this.isLoading = true;

            this.saveAll()
                .then(() => {
                    this.isLoading = false;
                    this.processSuccess = true
                }) .catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('neno.marketing.essentials.overview.errorTitle'),
                    message: exception
                });
            });
        },

        saveFinish() {
            this.processSuccess = false;
        },
    }
});
