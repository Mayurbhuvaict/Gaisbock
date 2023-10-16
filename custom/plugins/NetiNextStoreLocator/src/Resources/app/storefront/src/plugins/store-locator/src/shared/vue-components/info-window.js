import { emit } from '../util/events';

export default {
    template: '#neti-store-locator-info-window',
    props: {
        store: {
            type: Object,
            required: true
        },
        config: {
            type: Object,
            required: true
        }
    },

    watch: {
        store: {
            immediate: true,
            handler() {
                this.$nextTick(() => {
                    this.store.infoContent = this.$el.innerHTML;

                    emit('netiStoreLocator.infoWindow.contentUpdated', this.store);
                })
            }
        }
    },

    computed: {
        formattedOpeningTimes() {
            if (this.store.translated.openingTimes) {
                return this.store.translated.openingTimes
                    .trim()
                    .replace(/\n/g, '<br />');
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
        weekDays() {
            return this.store.extensions.storeBusinessHours.weekDays;
        },
    },

    methods: {
        formatTime(time) {
            return time.replace(/(:\d{2}| [AP]M)$/, "");
        },
    }
};
