import template from './neno-marketing-essentials-newsletter-popup-detail.html.twig';
import './neno-marketing-essentials-newsletter-popup-detail.scss'

const { Component, Mixin } = Shopware;

Component.register('neno-marketing-essentials-newsletter-popup-detail', {
    template,

    inject: [
        'repositoryFactory'
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            popup: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('neno_marketing_essentials_newsletter_popup');
        Promise.resolve(this.getPopup()).then(this.checkLanguage);
    },

    methods: {
        getPopup() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((popup) => {
                    this.popup = popup;
                });
        },

        checkLanguage() {
            if (this.popup.isNew()) {
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
            this.getPopup();
        },

        abortOnLanguageChange({ oldLanguageId, newLanguageId }) {
            if (oldLanguageId === newLanguageId) return false;
            return this.repository.hasChanges(this.popup);
        },

        saveOnLanguageChange() {
            return this.onClickSave();
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.popup, Shopware.Context.api)
                .then(() => {
                    this.getPopup();
                    this.isLoading = false;
                    this.processSuccess = true;
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
    },

    computed: {
        promotionIndividualCodesNotice() {
            return this.$tc('neno-marketing-essentials.newsletter-popup.detail.promotionIndividualCodesNotice');
        }
    }
});
