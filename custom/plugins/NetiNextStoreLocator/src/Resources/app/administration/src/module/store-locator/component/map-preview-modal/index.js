import template from './template.twig';
import './style.scss';

const { Component } = Shopware;

Component.register('neti-store-locator-map-preview-modal', {
    template,

    props: {
        store: {
            type: Object,
            required: true
        }
    },

    data: () => (
        {
            map: null,
            isOpen: false,
            config: null,
            newCoordinates: {
                lat: 0,
                lng: 0
            }
        }
    ),

    methods: {
        open() {
            this.isOpen         = true;
            this.newCoordinates = {
                lat: this.store.latitude || 0,
                lng: this.store.longitude || 0
            };

            this.loadConfig()
                .then(this.loadGoogleMaps)
                .then(this.createMap);
        },

        onApply() {
            this.store.latitude  = this.newCoordinates.lat;
            this.store.longitude = this.newCoordinates.lng;

            this.onClose();
        },

        onClose() {
            this.isOpen = false;
        },

        createMap() {
            this.map = new window.google.maps.Map(
                this.$refs.map,
                {
                    zoom: 15,
                    center: this.newCoordinates,
                    fullscreenControl: false,
                    mapTypeControl: false,
                    streetViewControl: false
                }
            );

            let marker = new window.google.maps.Marker(
                {
                    map: this.map,
                    draggable: true,
                    animation: window.google.maps.Animation.DROP,
                    position: this.newCoordinates
                }
            );

            window.google.maps.event.addListener(marker, 'dragend', () => {
                let pos = marker.getPosition();

                this.newCoordinates.lat = pos.lat();
                this.newCoordinates.lng = pos.lng();
            });
        },

        loadConfig() {
            return new Promise((resolve) => {
                if (this.config !== null) {
                    resolve();

                    return;
                }

                let httpClient = Shopware.Application.getContainer('init').httpClient;
                let headers    = {
                    Accept: 'application/vnd.api+json',
                    Authorization: `Bearer ${ Shopware.Context.api.authToken.access }`,
                    'Content-Type': 'application/json'
                };

                httpClient.get('_action/neti-store-locator/config', { headers }).then(({ data }) => {
                    this.config = data;
                    resolve();
                });
            });
        },

        loadGoogleMaps() {
            return new Promise((resolve) => {
                if (window.google && window.google.maps && window.google.maps.Map) {
                    resolve();

                    return;
                }

                window.NetiStoreLocatorMapInit = () => {
                    delete window.NetiStoreLocatorMapInit;
                    resolve();
                };

                const url = '//maps.googleapis.com/maps/api/js?v=3&libraries=places&language=de_DE&key='
                    + this.config.googleApiKey
                    + '&callback=window.NetiStoreLocatorMapInit';

                const script = document.createElement('script');
                script.src   = url;
                script.type  = 'text/javascript';

                document.head.appendChild(script);
            });
        }
    }
});
