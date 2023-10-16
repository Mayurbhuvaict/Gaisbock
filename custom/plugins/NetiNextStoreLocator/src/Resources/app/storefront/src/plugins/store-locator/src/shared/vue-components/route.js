import { on } from '../util/events';

export default {
    template: '#neti-store-locator-route',
    data() {
        return {
            isLoading: false,
            isError: false,
            route: null,
            modal: null
        }
    },
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
            required: true,
            type: Object
        }
    },
    computed: {
        routeLink() {
            return this.map.getRouteLink(this.store, this.location.geometry.location);
        }
    },
    mounted() {
        const me = this;

        on('netiStoreLocator.route', me.onOpen.bind(me));
    },
    methods: {
        onOpen() {
            if (null === this.modal) {
                this.modal = new bootstrap.Modal(this.$el);
            }

            this.modal.show();
            this.onLoad();
        },
        onLoad() {
            const me = this;

            me.isLoading = true;

            const request = {
                from: me.location.geometry.location,
                to: new window.google.maps.LatLng({
                    lat: me.store.latitude,
                    lng: me.store.longitude
                })
            };

            me.map.getRoute(request).then(route => {
                me.isLoading = false;
                me.route     = route;
            }).catch(() => {
                me.isError   = true;
                me.isLoading = false;
            });
        }
    }
};
