import { emit, once } from './util/events';
import { MarkerClusterer } from '@marker-clusterer';

let CookieConsentClass = null;

export function setCookieConsentClass (classReference) {
    CookieConsentClass = classReference;
}

export default class GoogleMap {
    constructor(config, $mapContainer, $map) {
        const me = this;

        me.cookieConsent = null;

        if (typeof CookieConsentClass === 'function') {
            me.cookieConsent = new CookieConsentClass(config);
        }

        me.config        = config;
        me.$mapContainer = $mapContainer;
        me.$map          = $map;

        me.map                      = null;
        me.infoWindow               = null;
        me.geoCoder                 = null;
        me.markerClusterer          = null;
        me.markerIconSize           = null;
        me.directionsService        = null;
        me.autocompleteService      = null;
        me.autocompleteSessionToken = null;
        me.placesService            = null;
        me.countries                = [];
        me.activeMarkers            = [];
        me.logEnabled               = 'development' === process.env.NODE_ENV;
        me.localMarker              = null;
    }

    /**
     * This method yet loads 2 external javascript libraries into the current document.
     *
     * When finished the map will be initialized.
     */
    async load() {
        const me = this;

        return new Promise(async(resolve, reject) => {
            if (null !== me.cookieConsent) {
                me.cookieConsent.ready({
                    abortCallback: (url) => {
                        location.href = url;
                    }
                }).then(async() => {
                    await this.loadLibraries();

                    me.init();
                    resolve();
                }).catch(reject);
            } else {
                await this.loadLibraries();

                me.init();
                resolve();
            }
        });
    }

    async loadLibraries() {
        return new Promise(async(resolve) => {
            window.NetiStoreLocatorMapInit = () => {
                delete window.NetiStoreLocatorMapInit;

                resolve();
            };

            await this.loadGoogleMapsLibrary();
        });
    }

    async loadGoogleMapsLibrary() {
        return new Promise((resolve, reject) => {
            if ('google' in window && 'maps' in window.google) {
                window.NetiStoreLocatorMapInit();
                resolve();
                return;
            }

            const me          = this;
            const script      = document.createElement('script');
            let languageParam = '';

            if (true === me.config.googleApiLangInheritFromDomain) {
                const language = document.querySelector('html').getAttribute('lang').replace('-', '_');

                languageParam = '&language=' + language;
            } else if (typeof me.config.googleApiLang === 'string' && '' !== me.config.googleApiLang) {
                languageParam = '&language=' + me.config.googleApiLang;
            }

            script.src = '//maps.googleapis.com/maps/api/js?v=3&libraries=places&key='
                + me.config.googleApiKey
                + '&callback=window.NetiStoreLocatorMapInit'
                + languageParam;

            script.type = 'text/javascript';
            document.head.appendChild(script);

            resolve();
        });
    }

    init() {
        const me             = this;
        const hasGoogleMapID = typeof me.config.googleMapID === 'string' && '' !== me.config.googleMapID;

        let mapConfig = {
            zoom: 3,
            center: { lat: 0, lng: 0 },
            fullscreenControl: false,
            mapTypeControl: false,
            streetViewControl: false,
            mapId: me.config.googleMapID
        };

        if (me.config.googleMapConfig) {
            try {
                const options = JSON.parse(this.config.googleMapConfig);

                mapConfig = {
                    ...mapConfig,
                    ...options
                };
            } catch (ex) {
                me.log('The given map config is invalid', ex, me.config.googleMapConfig);
            }
        }

        me.$mapContainer.style.minHeight = me.$map.height;

        me.map = new window.google.maps.Map(me.$map, mapConfig);

        me.infoWindow = new window.google.maps.InfoWindow({
            content: ''
        });

        me.geoCoder            = new window.google.maps.Geocoder();
        me.directionsService   = new window.google.maps.DirectionsService();
        me.autocompleteService = new window.google.maps.places.AutocompleteService();
        me.placesService       = new window.google.maps.places.PlacesService(me.map);

        if (me.config.googleMarkerClustering === true) {
            me.markerClusterer = new MarkerClusterer({
                map: me.map,
                markers: []
            });
        }

        if (me.config.googleMapOptions && false === hasGoogleMapID) {
            const options = eval(me.config.googleMapOptions);

            me.map.mapTypes.set('styled_map', new window.google.maps.StyledMapType(options));
            me.map.setMapTypeId('styled_map');
        }

        if (me.config.googleMapIconSize) {
            me.markerIconSize = new window.google.maps.Size(
                me.config.googleMapIconSize.width,
                me.config.googleMapIconSize.height
            );
        }
        
        if (true === me.config.showLocalMarker) {
            this.localMarker = new window.google.maps.Marker({
                title: me.config.localMarkerTitle
            });
            
            this.localMarker.addListener('click', () => {
                me.infoWindow.setContent(me.config.localMarkerTitle);
                me.infoWindow.open(me.map, this.localMarker);
            });
        }
    }
    
    toggleLocalMarker (location) {
        if (!this.localMarker) {
            return;
        }
        
        if (!location) {
            this.localMarker.setMap(null);
            return;
        }

        let { lat, lng } = location.geometry.location;
        const position = { lat: lat(), lng: lng() };
        
        this.localMarker.setPosition(position);
        this.localMarker.setMap(this.map);
    }

    initStore(store, opts) {
        const me       = this;
        const position = {
            lat: store.latitude,
            lng: store.longitude
        };
        const settings = Object.assign({
            infoWindow: true
        }, opts);

        const icon = {
            url: store.iconMedia
                ? store.iconMedia.url
                : me.config.googleMapIcon,
            scaledSize: me.markerIconSize
        };

        store.marker = new window.google.maps.Marker({
            position,
            title: store.label
        });

        if (icon.url) {
            store.marker.setIcon(icon);
        }

        store.marker.addListener('click', () => {
            emit('netiStoreLocator.storeMarker.click', {
                store,
                focus: false
            });

            if (settings.infoWindow === true) {
                me.showInfoWindow(store);
            }
        });

        store.marker.show = () => {
            store.marker.setMap(me.map);

            if (me.config.googleMarkerClustering === true) {
                me.activeMarkers.push(store.marker);
            }
        };

        store.marker.hide = () => {
            store.marker.setMap(null);
        };

    }

    clearActiveMarkers() {
        this.activeMarkers = [];
    }

    enableActiveMarkers() {
        if (true !== this.config.googleMarkerClustering) {
            return;
        }

        this.markerClusterer.clearMarkers();

        if (this.activeMarkers.length > 0) {
            this.markerClusterer.addMarkers(this.activeMarkers);
        }
    }

    showInfoWindow(store) {
        const me = this;

        if (undefined === store.infoContent) {
            once('netiStoreLocator.infoWindow.contentUpdated', me.showInfoWindow.bind(me));
            return;
        }

        me.infoWindow.setContent(store.infoContent);
        me.infoWindow.open(me.map, store.marker);
    }

    focusStore(store) {
        const me = this;

        me.map.setZoom(store.zoom || 15);
        me.map.setCenter({
            lat: store.latitude,
            lng: store.longitude
        });
    }

    isGeoLocationEnabled() {
        const me = this;

        if (!window.navigator.geolocation || !me.config) {
            return false;
        }

        const isMobile = window.matchMedia('only screen and (max-width: 760px)').matches;

        switch (me.config.getGeolocation) {
            case 'always':
                return true;
            case 'pc':
                return !isMobile;
            case 'mobile':
                return isMobile;
            default:
                return false;
        }
    }

    getBrowserLocation() {
        return new Promise((resolve, reject) => {
            window.navigator.geolocation.getCurrentPosition(pos => {
                const location = {
                    lat: pos.coords.latitude,
                    lng: pos.coords.longitude
                };

                resolve(location);
            }, reject);
        });
    }

    doLocate() {
        const me = this;

        if (!me.isGeoLocationEnabled()) {
            return;
        }

        return new Promise((resolve, reject) => {
            me.getBrowserLocation().then(location => {
                me.geoCode({ location }).then(result => {
                    resolve({
                        location,
                        result,
                        address: result.formatted_address
                    });
                });
            }).catch(reject);
        });
    }

    geoCode(request) {
        const me = this;

        return new Promise((resolve, reject) => {
            me.geoCoder.geocode(request, (results, status) => {
                if (status === 'OK') {
                    const result = results[0];

                    resolve(result);
                } else {
                    reject(status);
                }
            });
        });
    }

    setCenter(geometry) {
        const me = this;

        if (geometry.bounds) {
            me.map.fitBounds(geometry.bounds);
            me.map.panToBounds(geometry.bounds);
        } else {
            me.map.panTo(geometry.location);
            me.map.setZoom(10);
        }
    }

    /**
     * @deprecated Use loadPlacePredictions instead
     * @param element
     * @param callback
     */
    initAutocomplete(element, callback) {
        const me = this;

        me.autoComplete = new window.google.maps.places.Autocomplete(element, {});

        me.autoComplete.addListener('place_changed', () => {
            const result  = me.autoComplete.getPlace();
            const request = {
                address: 'geometry' in result
                    ? result.formatted_address
                    : result.name
            };

            me.geoCode(request).then(result => {
                callback({
                    location,
                    result,
                    address: result.formatted_address
                });
            });
        });
    }

    setCountries(countries) {
        const me = this;

        me.countries = countries;
    }

    createAutocompleteSessionToken() {
        const me = this;

        me.autocompleteSessionToken = new window.google.maps.places.AutocompleteSessionToken();
    }

    loadPlacePredictions(input) {
        const me = this;

        if (!me.autocompleteSessionToken) {
            me.createAutocompleteSessionToken();
        }

        return new Promise((resolve, reject) => {
            const request = {
                input,
                sessionToken: me.autocompleteSessionToken
            };

            if (me.countries.length === 1) {
                request.componentRestrictions = {
                    country: me.countries[0]
                };
            }

            if (!input.length) {
                resolve([]);
                return;
            }

            me.autocompleteService.getPlacePredictions(request, (response, status) => {
                if (status === window.google.maps.places.PlacesServiceStatus.OK) {
                    resolve(response || []);
                } else if (status === window.google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
                    resolve([]);
                } else {
                    reject(status);
                }
            });
        });
    }

    loadPlace(placeId) {
        const me = this;

        if (!me.autocompleteSessionToken) {
            me.createAutocompleteSessionToken();
        }

        return new Promise((resolve, reject) => {
            const request = {
                placeId,
                fields: ['geometry']
            };

            me.placesService.getDetails(request, (response, status) => {
                if (status === window.google.maps.places.PlacesServiceStatus.OK) {
                    resolve(response);
                } else {
                    reject(status);
                }
            });
        });
    }

    getRoute({ from, to }) {
        const me = this;

        return new Promise((resolve, reject) => {
            const request = {
                origin: from,
                destination: to,
                travelMode: window.google.maps.TravelMode.DRIVING
            };

            me.directionsService.route(request, (response, status) => {
                if (status === window.google.maps.DirectionsStatus.OK) {
                    if (response.routes.length > 0) {
                        const route = response.routes[0];

                        if (route.legs.length > 0) {
                            resolve(route.legs[0]);
                            return;
                        }
                    }
                }

                reject(status);
            });
        });
    }

    getRouteLink(store, location) {
        const start = location.toString();
        const end   = encodeURIComponent(
            [
                store.street,
                store.streetNumber,
                store.zipCode,
                store.city,
                (
                    store.country ? store.country.name : ''
                )
            ].filter(i => typeof i == 'string' && i.length > 0).join(',')
        );

        return 'https://www.google.com/maps/dir/' + encodeURIComponent(start) + '/' + end + '/am=t';
    }

    log(...args) {
        if (!this.logEnabled) {
            return;
        }

        console.log('[store-locator::google-map]', ...args);
    }
}
