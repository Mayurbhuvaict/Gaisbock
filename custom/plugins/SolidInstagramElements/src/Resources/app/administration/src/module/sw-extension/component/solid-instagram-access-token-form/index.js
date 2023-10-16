import template from './solid-instagram-access-token-form.html.twig';
import './solid-instagram-access-token-form.scss';

const { Component } = Shopware;

/**
 * @private
 */
Component.register('solid-instagram-access-token-form', {
    template,

    props: {
        value: {
            type: Object,
            default: () => {
                return {
                    accessToken: '',
                    userId: '',
                    username: '',
                    lastRefreshed: '',
                    expiresIn: '',
                    isValid: false,
                };
            },
        },
    },

    data() {
        return {
            testingAccessToken: false,
            finishedTestingAccessToken: false,
            refreshingAccessToken: false,
            finishedRefreshingAccessToken: false,
            requestWasSuccessful: false,
            alertText: '',
            alertInfo: '',
        };
    },

    computed: {
        expiresInDays() {
            return parseInt(this.value.expiresIn / 60 / 60 / 24, 10);
        },
    },

    async created() {
        if (this.value.accessToken) {
            this.onTestAccessToken();
        }
    },

    methods: {
        onInput(value) {
            this.$emit('input', {
                accessToken: value,
                userId: '',
                username: '',
                lastRefreshed: '',
                expiresIn: '',
                isValid: false,
            });

            this.finishedTestingAccessToken = false;
            this.finishedRefreshingAccessToken = false;
        },

        async onTestAccessToken() {
            this.requestWasSuccessful = false;
            this.finishedTestingAccessToken = false;
            this.finishedRefreshingAccessToken = false;
            this.testingAccessToken = true;
            this.alertText = '';
            this.alertInfo = '';

            try {
                const response = await fetch(
                    `https://graph.instagram.com/me?fields=id,username&access_token=${
                        this.value.accessToken}`,
                );

                if (!response.ok) {
                    throw new Error({
                        text: this.$t(
                            'sw-extension.component.solid-instagram-access-token-form.alert.errorTesting',
                        ),
                        status: response.status,
                    });
                }

                if (response.ok) {
                    const data = await response.json();

                    this.$emit('input', {
                        accessToken: this.value.accessToken,
                        userId: data.id,
                        username: data.username,
                        lastRefreshed: this.value.lastRefreshed,
                        expiresIn: this.value.expiresIn,
                        isValid: true,
                    });

                    this.requestWasSuccessful = true;
                    this.alertText = this.$t(
                        'sw-extension.component.solid-instagram-access-token-form.alert.successTesting',
                    );
                }
            } catch (error) {
                this.alertText = error.text || error;

                if (error.status) {
                    this.alertInfo = this.getStatusMessage(error.status);
                }
            }

            this.testingAccessToken = false;
            this.finishedTestingAccessToken = true;
        },

        async onRefreshAccessToken() {
            this.requestWasSuccessful = false;
            this.finishedTestingAccessToken = false;
            this.finishedRefreshingAccessToken = false;
            this.refreshingAccessToken = true;
            this.alertText = '';
            this.alertInfo = '';

            try {
                const response = await fetch(
                    `https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=${
                        this.value.accessToken}`,
                );

                if (!response.ok) {
                    throw new Error({
                        text: this.$t(
                            'sw-extension.component.solid-instagram-access-token-form.alert.errorRefreshing',
                        ),
                        status: response.status,
                    });
                }

                if (response.ok) {
                    const data = await response.json();

                    this.$emit('input', {
                        accessToken: this.value.accessToken,
                        userId: this.value.userId,
                        username: this.value.username,
                        lastRefreshed: new Date().toISOString().split('T')[0],
                        expiresIn: data.expires_in,
                        isValid: true,
                    });

                    this.requestWasSuccessful = true;
                    this.alertText = this.$t(
                        'sw-extension.component.solid-instagram-access-token-form.alert.successRefreshing',
                    );
                }
            } catch (error) {
                this.alertText = error.text || error;

                if (error.status) {
                    this.alertInfo = this.getStatusMessage(error.status);
                }
            }

            this.refreshingAccessToken = false;
            this.finishedRefreshingAccessToken = true;
        },

        getStatusMessage(status) {
            let message = this.$t(
                'sw-extension.component.solid-instagram-access-token-form.alert.statusCode',
                { status: status },
            );

            switch (status) {
                case 400:
                    message +=
                        `: ${
                            this.$t(
                                'sw-extension.component.solid-instagram-access-token-form.alert.status.400',
                            )}`;
                    break;
                case 404:
                    message +=
                        `: ${
                            this.$t(
                                'sw-extension.component.solid-instagram-access-token-form.alert.status.404',
                            )}`;
                    break;

                default:
                    break;
            }

            return message;
        },
    },
});
