import template from './template.twig';
import './style.scss';

const { Component } = Shopware;

Component.register('neti-store-locator-update-coordinates-modal', {
    template,

    data() {
        return {
            isOpen: false,
            isRunning: false,
            isAborting: true,
            isFinished: false,
            failedAt: null,

            error: null,
            hasTimezoneError: false,

            itemIndex: 0,
            itemVariant: 'disabled',

            progress: {
                value: 0,
                total: 0
            },
            
            chunkSize: 25
        };
    },

    methods: {
        reset() {
            let me = this;

            me.isAborting = false;
            me.failedAt   = null;
            me.isFinished = false;
            me.hasTimezoneError = false;

            me.itemIndex   = 0;
            me.itemVariant = 'disabled';

            me.progress.value = 0;
            me.progress.total = 0;

            me.$refs.stepDisplay.items.forEach(item => {
                item.setActive(false);
                item.setVariant('disabled');
            });
        },
        skipAll() {
            let me = this;

            me.$refs.stepDisplay.items.forEach(item => {
                item.setActive(true);
                item.setVariant('success');
            });
        },
        open() {
            let me = this;

            me.reset();

            me.isOpen = true;
        },
        run() {
            let me = this;
            
            if (me.chunkSize <= 0) {
                me.chunkSize = 25;
            }

            me.reset();
            me.isRunning  = true;

            me.loadStoreIds().then(result => {
                me.itemVariant    = 'success';
                me.progress.value = 0;
                me.progress.total = result.total;

                let data = Object.values(result.data);

                if (!data.length) {
                    me.skipAll();
                    me.isRunning  = false;
                    me.isFinished = true;

                    return;
                }

                me.$nextTick(() => {
                    // Set active item to "Processing"
                    me.itemVariant = 'disabled';
                    me.itemIndex   = 1;

                    function repeat() {
                        let ids = data.splice(0, me.chunkSize); // Fetches X items from array

                        me.locateIds(ids).then((responseData) => {
                            if (responseData.hasTimezoneError) {
                                me.hasTimezoneError = true;
                            }

                            if (!responseData.success) {
                                me.onError(responseData.message);
                                return;
                            }

                            me.progress.value += ids.length;

                            if (data.length > 0) { // Continue requests until the array is empty
                                repeat();
                            } else {
                                me.progress.value = me.progress.total;

                                // Defines the variant of the current active item
                                me.itemVariant = 'success';

                                me.$nextTick(() => {
                                    // Set active item to "Finished"
                                    me.itemIndex = 2;

                                    // Reactivate the run button
                                    me.isRunning  = false;
                                    me.isFinished = true;
                                });
                            }
                        }).catch(me.onError);
                    }

                    repeat();
                });
            }).catch(me.onError);
        },
        abort() {
            let me = this;

            me.reset();

            me.isRunning  = false;
            me.isAborting = true;
        },
        onClose() {
            let me = this;

            if (me.isRunning) {
                return;
            }

            me.isOpen = false;
        },
        onError(error) {
            let me = this;

            if (me.isAborting) {
                me.isAborting = false;
                return;
            }

            me.isRunning = false;
            me.failedAt  = true;
            me.error     = error;
        },
        locateIds(ids) {
            let me         = this;
            let httpClient = Shopware.Application.getContainer('init').httpClient;
            let headers    = {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${ Shopware.Context.api.authToken.access }`,
                'Content-Type': 'application/json'
            };

            if (me.isAborting) {
                return false;
            }

            return new Promise((resolve, reject) => {
                httpClient.post('_action/neti-store-locator/locateIds', ids, { headers }).then(({ data }) => {
                    resolve(data);
                }).catch(reject);
            });
        },
        loadStoreIds() {
            let httpClient = Shopware.Application.getContainer('init').httpClient;
            let headers    = {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${ Shopware.Context.api.authToken.access }`,
                'Content-Type': 'application/json'
            };

            return new Promise((resolve, reject) => {
                httpClient.get('_action/neti-store-locator/ids', { headers }).then(({ data }) => {
                    resolve(data);
                }).catch(reject);
            });
        }
    }
});
