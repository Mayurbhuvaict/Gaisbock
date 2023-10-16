import template from './neno-marketing-essentials-register-popup-detail.html.twig';
import './neno-marketing-essentials-register-popup-detail.scss'

const { Component, Mixin } = Shopware;

Component.register('neno-marketing-essentials-register-popup-detail', {
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
            registerPopup: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('neno_marketing_essentials_register_popup');
        Promise.resolve(this.getRegisterPopup()).then(this.checkLanguage);
    },

    methods: {
        getRegisterPopup() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((registerPopup) => {
                    this.registerPopup = registerPopup;
                });
        },

        checkLanguage() {
            if (this.registerPopup.isNew()) {
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
            this.getRegisterPopup();
        },

        abortOnLanguageChange({ oldLanguageId, newLanguageId }) {
            if (oldLanguageId === newLanguageId) return false;
            return this.repository.hasChanges(this.registerPopup);
        },

        saveOnLanguageChange() {
            return this.onClickSave();
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.registerPopup, Shopware.Context.api)
                .then(() => {
                    this.getRegisterPopup();
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
    },

    computed: {
        promotionIndividualCodesNotice() {
            return this.$tc('neno-marketing-essentials.register-popup.detail.promotionIndividualCodesNotice');
        }
    }
});
