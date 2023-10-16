import { nl2br, scrollTo } from '../util';
import { emit, on, once } from '../util/events';

export default {
    template: '#neti-store-locator-store',
    props: {
        store: {
            required: true,
            type: Object
        },
        map: {
            required: true,
            type: Object
        },
        location: {
            required: false,
            type: Object
        },
        selected: {
            required: false,
            type: Boolean
        },
        config: {
            required: true,
            type: Object
        },
        horizontal: {
            required: false,
            type: Boolean
        },
        siblings: {
            required: false,
            type: Array,
            default() {
                return [];
            }
        },
    },
    data() {
        return {
            isClosing: false
        }
    },
    computed: {
        detailLink() {
            return this.config.storeUrl.replace('storeId', this.store.id);
        },
        routeLink() {
            if (this.config.routeOnGoogleMaps && this.location) {
                return this.map.getRouteLink(this.store, this.location.geometry.location);
            }
            return null;
        },
        hasBusinessHours() {
            const businessHours = this.store.extensions.storeBusinessHours.businessHours;

            return businessHours !== undefined
                && businessHours !== null
                && typeof businessHours === 'object'
                && !Array.isArray(businessHours);
        },
        businessHours() {
            return this.store.extensions.storeBusinessHours.businessHours;
        },
        isStoreOpen() {
            return this.store.extensions.storeBusinessHours.isStoreOpen;
        },
        isDifferentTimeZone() {
            if (null === this.store.timezone) {
                return false;
            }

            const date       = new Date();
            const storeTime  = date.toLocaleTimeString('de-DE', {timeZone: this.store.timezone});
            const clientTime = date.toLocaleTimeString('de-DE', {timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone});

            return storeTime !== clientTime;
        },
        weekDays() {
            return this.store.extensions.storeBusinessHours.weekDays;
        },
        additionalInformation() {
            if (this.store.translated.additionalInformation) {
                return this.store.translated.additionalInformation;
            }

            return null;
        },
        formattedOpeningTimes() {
            if (this.store.translated.openingTimes) {
                return this.store.translated.openingTimes
                    .trim()
                    .replace(/\n/g, '<br />')
            }

            return null;
        },
        isStoreSelected() {
            return typeof this.store.extensions.netiStorePickupSelected === 'object'
                && this.store.extensions.netiStorePickupSelected !== null;
        }
    },
    mounted() {
        const me = this;

        window.addEventListener('resize', me.onResize.bind(me));
        me.onResize();

        on('netiStoreLocator.storeMarker.click', ({ store }) => {
            if (!store || store.id !== me.store.id) {
                return;
            }

            me.doScroll();
        });

        if (me.horizontal) {
            on('netiStoreLocator.horizontalStore.calculateView', () => {
                const offsetLeft  = me.$el.offsetLeft - me.$el.parentNode.scrollLeft; // The actual X pos on the screen
                const screenWidth = window.outerWidth;
                const offsetWidth = offsetLeft < 0 ? me.$el.offsetWidth + offsetLeft : screenWidth - 10 - offsetLeft; // The visible width on the screen

                me.store._viewPercentage = Math.min(100, offsetWidth / me.$el.offsetWidth * 100);
            })

            on('netiStoreLocator.horizontalStore.scrollTo', _store => {
                if (_store.id === me.store.id) {
                    me.doScroll('fast');
                }
            });
        }
    },
    beforeDestroy() {
        const me = this;

        window.removeEventListener('resize', me.onResize.bind(me));
    },
    methods: {
        formatTime(time) {
            return time.replace(/(:\d{2}| [AP]M)$/, "");
        },
        onResize() {
            const me = this;

            if (me.horizontal) {
                const width = window.outerWidth * 0.8;

                me.$el.style.width = width + 'px';
            }
        },
        onMarkerClicked() {
            const me = this;

            emit('netiStoreLocator.storeMarker.click', {
                store: me.store,
                focus: true
            });
        },
        onContact() {
            emit('netiStoreLocator.contact');
        },
        onRoute() {
            emit('netiStoreLocator.route');
        },
        onClick() {
            const me = this;

            if (me.isClosing) {
                me.isClosing = false;
                return;
            }

            /**
             * When we have a horizontal store, a click into that store should activate a scroll-to-middle of the store.
             */
            if (me.horizontal) {
                me.doScroll();

                emit('netiStoreLocator.storeMarker.click', {
                    store: me.store,
                    focus: true
                });
            }
        },
        onSelectStore() {
            emit('netiStoreLocator.store.select', {
                store: this.store
            });
        },
        onClose() {
            this.isClosing = true;

            emit('netiStoreLocator.storeMarker.click', {
                store: null,
                focus: false
            });
        },
        doScroll(fast = false) {
            const me = this;

            if (me.horizontal) {
                setTimeout(() => {
                    let scrollPos      = me.$el.offsetLeft;
                    const currentIndex = me.siblings.indexOf(me.store);

                    if (currentIndex === 0) {
                        /**
                         * When scrolling to the first element in the row, the scrollPos simply equals zero.
                         * @type {number}
                         */
                        scrollPos = 0;
                    } else if (currentIndex > 0 && currentIndex < me.siblings.length - 1) {
                        /**
                         * When scrolling to a certain element which is not the first or last element in the row, we are
                         * calculating the scrollPos a bit differently to center the store.
                         * @type {number}
                         */
                        scrollPos -= (
                            window.outerWidth - me.$el.offsetWidth
                        ) / 2;
                    }

                    // The element is not yet visible...
                    if (me.$el.offsetParent === null) {
                        // Wait until its visible...
                        once('netiStoreLocator.toggleStores', (value) => {
                            setTimeout(() => {
                                if (value === false && me.selected === true) {
                                    me.doScroll(fast);
                                }
                            }, 0);
                        });

                        return;
                    }

                    scrollTo(scrollPos, fast ? 150 : 500, me.$el.parentNode, 'left');
                });
            } else {
                setTimeout(() => {
                    scrollTo(me.$el.offsetTop, fast ? 150 : 500, me.$el.parentNode);
                }, 0);
            }
        }
    }
};