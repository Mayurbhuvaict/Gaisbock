import { emit } from '../util/events';
import GoogleMap from '../google-map';

export default function() {
    return {
        el: '.neti-next-store-locator-detail',
        name: 'Detail',
        data() {
            return {
                map: null,
                store: null
            };
        },
        mounted() {
            const me = this;

            me.config = JSON.parse(me.$refs.config.value);
            me.store  = JSON.parse(me.$refs.store.value);

            me.initMap();
        },
        methods: {
            initMap() {
                const me = this;

                me.map = new GoogleMap(me.config, me.$refs.mapContainer, me.$refs.map);
                me.map.load().then(me.onMapLoaded.bind(me));
            },
            onMapLoaded() {
                const me = this;

                me.map.initStore(me.store, {
                    infoWindow: false
                });

                me.store.marker.show();
                me.map.focusStore(me.store);
            },
            onContactForm() {
                emit('netiStoreLocator.contact');
            }
        }
    };
};