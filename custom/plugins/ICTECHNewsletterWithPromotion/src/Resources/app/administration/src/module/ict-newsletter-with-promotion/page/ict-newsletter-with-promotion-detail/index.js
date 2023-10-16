import template from './ict-newsletter-with-promotion-detail.html.twig';
import './ict-newsletter-with-promotion-detail.scss';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();


Component.register('ict-newsletter-with-promotion-detail',{
    template,
    inject: ['repositoryFactory'],
    mixins: [
        Mixin.getByName('placeholder'),
        Mixin.getByName('notification'),
        Mixin.getByName('discard-detail-page-changes')('newsletter_popup'),
    ],
    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onCancel',
    },

    props: {
        popupId: {
            type: String,
            required: false,
            default: null,
        },
    },

    data() {
        return {
            popup: null,
            isLoading: false,
            isSaveSuccessful: false,
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier),
        };
    },
    computed: {
        identifier() {
            return this.placeholder(this.popup, 'name');
        },

        popupIsLoading() {
            return this.isLoading || this.popup == null;
        },

        popupRepository() {
            return this.repositoryFactory.create('newsletter_popup');
        },

        mediaRepository() {
            return this.repositoryFactory.create('media');
        },

        tooltipSave() {

            const systemKey = this.$device.getSystemKey();

            return {
                message: `${systemKey} + S`,
                appearance: 'light',
            };
        },

        tooltipCancel() {
            return {
                message: 'ESC',
                appearance: 'light',
            };
        },

        promotionIndividualCodesNotice() {
            return this.$tc('ict-newsletter-with-promotion.newsletter-popup.detail.promotionIndividualCodesNotice');
        },

        ...mapPropertyErrors('popup', ['name']),
    },
    watch: {
        popupId() {
            this.createdComponent();
        },
    },
    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            Shopware.ExtensionAPI.publishData({
                id: 'newsletter-popup-detail__popup',
                path: 'ict.newsletter.with.promotion.index',
                scope: this,
            });
            if (this.popupId) {
                this.loadEntityData();
                return;
            }

            Shopware.State.commit('context/resetLanguageToDefault');
            this.popup = this.popupRepository.create();
        },

        async loadEntityData() {
            this.isLoading = true;

            const [popupResponse] = await Promise.allSettled([
                this.popupRepository.get(this.popupId),
            ]);

            if (popupResponse.status === 'fulfilled') {
                this.popup = popupResponse.value;
            }

            if (popupResponse.status === 'rejected') {
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationLoadingDataErrorMessage',
                    ),
                });
            }

            this.isLoading = false;
        },

        abortOnLanguageChange() {
            return this.popupRepository.hasChanges(this.popup);
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.loadEntityData();
        },

        setMediaItem({ targetId }) {
            this.popup.mediaImageId = targetId;
        },

        setMediaFromSidebar(media) {
            this.popup.mediaImageId = media.id;
        },

        onUnlinkLogo() {
            this.popup.mediaImageId = null;
        },

        openMediaSidebar() {
            this.$refs.mediaSidebarItem.openContent();
        },

        onDropMedia(dragData) {
            this.setMediaItem({ targetId: dragData.id });
        },

        onSave() {

            this.isLoading = true;

            this.popupRepository.save(this.popup).then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
                if (this.popupId === null) {
                    this.$router.push({ name: 'ict.newsletter.with.promotion.detail', params: { id: this.popup.id } });
                    return;
                }

                this.loadEntityData();
            }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.notificationSaveErrorMessageRequiredFieldsInvalid',
                    ),
                });
                throw exception;
            });
        },

        onCancel() {
            this.$router.push({ name: 'ict.newsletter.with.promotion.index' });
        },
    },
})