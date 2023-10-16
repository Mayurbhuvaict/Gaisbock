Shopware.Component.extend('neno-marketing-essentials-register-popup-create', 'neno-marketing-essentials-register-popup-detail', {
    methods: {
        getRegisterPopup() {
            this.registerPopup = this.repository.create(Shopware.Context.api);

            // Default values
            this.registerPopup.isGlobal = false
            this.registerPopup.devMode = false
            this.registerPopup.storageType = 'sessionStorage'
            this.registerPopup.popupTrigger = 'time'
            this.registerPopup.popupTime = 2
            this.registerPopup.popupScroll = 400
            this.registerPopup.heightMobile = 400
            this.registerPopup.heightDesktop = 500

            this.registerPopup.mediaBackgroundColor = '#f5f5f5'
            this.registerPopup.imagePosition = 'left'
            this.registerPopup.imageFit = 'cover'
            this.registerPopup.imageAlignment = 'center'
            this.registerPopup.imageMobileSettings = 'none'

            this.registerPopup.headline = 'Headline'
            this.registerPopup.subline = 'Subline'
            this.registerPopup.promotionTextValidUntil = 'Valid until'
            this.registerPopup.textSubmitButton = 'Register now'
            this.registerPopup.textNonSubmit = 'No thanks'
            this.registerPopup.contentAlignment= 'flex-start'

            this.registerPopup.headlineFontSizeMobile = 28
            this.registerPopup.headlineLineHeightMobile = 40
            this.registerPopup.headlineFontSizeTablet = 34
            this.registerPopup.headlineLineHeightTablet = 46
            this.registerPopup.headlineFontSizeDesktop = 40
            this.registerPopup.headlineLineHeightDesktop = 56

            this.registerPopup.sublineFontSizeMobile = 18
            this.registerPopup.sublineLineHeightMobile = 28
            this.registerPopup.sublineFontSizeTablet = 20
            this.registerPopup.sublineLineHeightTablet = 32
            this.registerPopup.sublineFontSizeDesktop = 20
            this.registerPopup.sublineLineHeightDesktop = 28

            this.registerPopup.promotionFontSizeMobile = 18
            this.registerPopup.promotionLineHeightMobile = 28
            this.registerPopup.promotionFontSizeTablet = 18
            this.registerPopup.promotionLineHeightTablet = 28
            this.registerPopup.promotionFontSizeDesktop = 18
            this.registerPopup.promotionLineHeightDesktop = 28

            this.registerPopup.backgroundColor = '#ffffff'
            this.registerPopup.closeButtonColor = '#909090'
            this.registerPopup.closeButtonHoverColor = '#000000'

            this.registerPopup.popupBorderRadius = 0
            this.registerPopup.submitButtonBorderRadius = 0

            this.registerPopup.promotionColor = '#000000'
            this.registerPopup.submitButtonBackgroundColor = '#000000'
            this.registerPopup.submitButtonBackgroundHoverColor = '#909090'
            this.registerPopup.submitButtonTextColor = '#ffffff'
            this.registerPopup.submitButtonTextHoverColor = '#ffffff'
            this.registerPopup.nonSubmitTextColor = '#000000'
            this.registerPopup.nonSubmitTextHoverColor = '#909090'
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.registerPopup, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({
                        name: 'neno.marketing.essentials.overview.registerPopupDetail',
                        params: { id: this.registerPopup.id }
                    });
                }) .catch((exception) => {
                console.log(exception);
                this.isLoading = false;

                this.createNotificationError({
                    title: "Test",
                    message: exception
                });
            });
        },
    }
})
