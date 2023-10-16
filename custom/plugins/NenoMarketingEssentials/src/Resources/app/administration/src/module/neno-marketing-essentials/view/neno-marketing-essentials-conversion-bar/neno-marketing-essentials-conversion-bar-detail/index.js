import template from './neno-marketing-essentials-conversion-bar-detail.html.twig';
import './neno-marketing-essentials-conversion-bar-detail.scss'

const { Component, Mixin, Data } = Shopware;
const { Criteria } = Data;
const DOMAIN = 'NenoMarketingEssentials.config';

Component.register('neno-marketing-essentials-conversion-bar-detail', {
    template,

    inject: [
        'systemConfigApiService',
        'repositoryFactory'
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
        };
    },

    created() {
        this.loadConversionBar()
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('neno_marketing_essentials_conversion_bar');
        },
    },

    methods: {
        loadConversionBar() {
            this.isLoading = true;

            // try load data from repository
            const criteria = new Criteria();
            criteria.setLimit(1);
            criteria.addFilter(Criteria.equals('salesChannelId', this.currentSalesChannelId))

            this.repository
                .search(criteria, Shopware.Context.api)
                .then((res) => {
                    if (res.length) {
                        return res.first();
                    }
                    return null;
                })
                .then((entity) => {
                    if (entity) {
                        this.conversionBar = entity;
                    } else {
                        this.createNew();
                    }
                })
                .then(() => {
                    this.checkLanguage();
                })
                .finally(() => {
                    this.isLoading = false;
                })
        },

        createNew() {
            this.conversionBar = this.repository.create(Shopware.Context.api);

            this.conversionBar.salesChannelId = this.currentSalesChannelId;

            // Default values
            this.conversionBar.active = false
            this.conversionBar.sliderMaxWidth = 700
            this.conversionBar.backgroundColor = '#000000'
            this.conversionBar.textColor = '#ffffff'
            this.conversionBar.linkColor = '#ffffff'

            this.conversionBar.text01 = ""
            this.conversionBar.text01Clickable = false
            this.conversionBar.text01Url = ""
            this.conversionBar.text01PrimaryActive = false
            this.conversionBar.text01Primary = ""
            this.conversionBar.text01PrimaryUrl = ""
            this.conversionBar.text01MediaId = null

            this.conversionBar.text02 = ""
            this.conversionBar.text02Clickable = false
            this.conversionBar.text02Url = ""
            this.conversionBar.text02PrimaryActive = false
            this.conversionBar.text02Primary = ""
            this.conversionBar.text02PrimaryUrl = ""
            this.conversionBar.text02MediaId = null

            this.conversionBar.text03 = ""
            this.conversionBar.text03Clickable = false
            this.conversionBar.text03Url = ""
            this.conversionBar.text03PrimaryActive = false
            this.conversionBar.text03Primary = ""
            this.conversionBar.text03PrimaryUrl = ""
            this.conversionBar.text03MediaId = null
        },

        checkLanguage() {
            if (this.conversionBar.isNew()) {
                const isSystemDefaultLang = Shopware.State.getters['context/isSystemDefaultLanguage'];

                if (!isSystemDefaultLang) {
                    Shopware.State.commit('context/resetLanguageToDefault');
                    console.log('after commit', Shopware.State.getters['context/isSystemDefaultLanguage']);
                }

                this.$nextTick(() => {
                    // This is a hack to update the language switch,
                    // since it is not reactive to the global language state
                    this.$refs.langSwitch.createdComponent();
                });
            }
        },

        onChangeLanguage() {
            this.loadConversionBar();
        },

        abortOnLanguageChange({ oldLanguageId, newLanguageId }) {
            if (oldLanguageId === newLanguageId) return false;
            return this.repository.hasChanges(this.conversionBar);
        },

        saveOnLanguageChange() {
            return this.onClickSave();
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
            this.loadConversionBar();
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.conversionBar, Shopware.Context.api)
                .then(() => {
                    this.loadConversionBar();
                    this.processSuccess = true;
                })
                .catch((exception) => {
                    console.error(exception)
                })
                .finally(() => {
                    this.isLoading = false;
                })
        },

        saveFinish() {
            this.processSuccess = false;
        },
    }
});
