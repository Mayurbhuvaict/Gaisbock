Shopware.Component.extend('neno-marketing-essentials-newsletter-popup-create', 'neno-marketing-essentials-newsletter-popup-detail', {
    methods: {
        getPopup() {
            this.popup = this.repository.create(Shopware.Context.api);

            // Default values
            this.popup.isGlobal = false
            this.popup.storageType = "sessionStorage"
            this.popup.devMode = false
            this.popup.popupTrigger = 'time'
            this.popup.popupTime = 2
            this.popup.popupScroll = 400
            this.popup.heightMobile = 400
            this.popup.heightDesktop = 500
            this.popup.showFirstName = true
            this.popup.showLastName = true

            this.popup.mediaBackgroundColor = '#f5f5f5'
            this.popup.imagePosition = 'left'
            this.popup.imageFit = 'cover'
            this.popup.imageAlignment = 'center'
            this.popup.imageMobileSettings = 'none'

            this.popup.headline = 'Headline'
            this.popup.subline = 'Subline'
            this.popup.firstNameFieldPlaceholder = 'Firstname'
            this.popup.lastNameFieldPlaceholder = 'Lastname'
            this.popup.mailFieldPlaceholder = 'Email address'
            this.popup.textSubscribeButton = 'Subscribe'
            this.popup.textNonSubscribe = 'No thanks'
            this.popup.contentAlignment= 'flex-start'

            this.popup.headlineFontSizeMobile = 28
            this.popup.headlineLineHeightMobile = 40
            this.popup.headlineFontSizeTablet = 34
            this.popup.headlineLineHeightTablet = 46
            this.popup.headlineFontSizeDesktop = 40
            this.popup.headlineLineHeightDesktop = 56

            this.popup.sublineFontSizeMobile = 18
            this.popup.sublineLineHeightMobile = 28
            this.popup.sublineFontSizeTablet = 20
            this.popup.sublineLineHeightTablet = 32
            this.popup.sublineFontSizeDesktop = 20
            this.popup.sublineLineHeightDesktop = 28

            this.popup.promotionFontSizeMobile = 18
            this.popup.promotionLineHeightMobile = 28
            this.popup.promotionFontSizeTablet = 18
            this.popup.promotionLineHeightTablet = 28
            this.popup.promotionFontSizeDesktop = 18
            this.popup.promotionLineHeightDesktop = 28

            this.popup.popupBorderRadius = 0
            this.popup.firstNameFieldBorderRadius = 0
            this.popup.lastNameFieldBorderRadius = 0
            this.popup.mailFieldBorderRadius = 0
            this.popup.subscribeButtonBorderRadius = 0

            this.popup.backgroundColor = '#ffffff'
            this.popup.closeButtonColor = '#909090'
            this.popup.closeButtonHoverColor = '#000000'
            this.popup.promotionColor = '#000000'
            this.popup.firstNameFieldBorderColor = '#909090'
            this.popup.lastNameFieldBorderColor = '#909090'
            this.popup.mailFieldBorderColor = '#909090'
            this.popup.subscribeButtonBackgroundColor = '#000000'
            this.popup.subscribeButtonBackgroundHoverColor = '#909090'
            this.popup.subscribeButtonTextColor = '#ffffff'
            this.popup.subscribeButtonTextHoverColor = '#ffffff'
            this.popup.nonSubscribeTextColor = '#000000'
            this.popup.nonSubscribeTextHoverColor = '#909090'
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.popup, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({
                        name: 'neno.marketing.essentials.overview.newsletterPopupDetail',
                        params: { id: this.popup.id }
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
