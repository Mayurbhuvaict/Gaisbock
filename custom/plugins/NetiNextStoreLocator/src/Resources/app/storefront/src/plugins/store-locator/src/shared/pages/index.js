import emitter, { emit, on } from '../util/events';
import { distanceBetweenPoints, scrollTo } from '../util';
import GoogleMap from '../google-map';
import FilterService from '../filters';
import HttpClient from '../http-client';

export default function({ HttpGateway }) {
    return {
        el: '.neti-next-store-locator',
        name: 'Listing',
        data() {
            return {
                logEnabled: 'development' === process.env.NODE_ENV,
                config: {},
                countries: [],
                map: null,
                filterService: null,

                stores: [],
                lastStoresQuery: null,

                selectedStore: null,
                isLoadingStores: false,
                storesWereLoadedOnce: false,

                searchInput: '',
                searchInputFocused: false,
                searchInputTimeout: null,
                autocompleteResults: [],
                countryFilterInput: '',

                currentLocation: null,

                filter: {
                    opened: false,
                    offCanvas: false
                },

                searchFilter: {
                    radius: 0,
                    countryId: null,
                    orderBy: {
                        id: null,
                        direction: null
                    },

                    storeId: null,
                    city: null,
                    tag: null,
                    storeName: null,
                    customFields: null,
                    onlyFeatured: false,

                    customFilters: {},
                    customFilterVisible: true,
                    customFilterCount: 0
                },

                storeListing: false,
                scrollTimeout: null,

                isMobile: !matchMedia('screen and (min-width: 1024px)').matches,
                preventLocate: false,
                pendingSearch: false,

                isLocating: false,
                emitter
            };
        },
        setup() {
            const httpClient = new HttpClient(new HttpGateway());

            return {
                httpClient
            };
        },
        async mounted() {
            const me = this;

            await me.loadConfig();

            me.filterService = new FilterService(
                me.searchFilter,
                me.config,
                me.$set
            );

            me.onReset();
            me.showFiltersIfRequired();

            me.map = new GoogleMap(me.config, me.$refs.mapContainer, me.$refs.map);

            me.map.load()
                .then(me.onMapLoaded.bind(me))
                .then(() => {
                    if (me.config.autoloadStores) {
                        me.isLoadingStores      = true;
                        me.storesWereLoadedOnce = true;

                        me.loadStores().then(me.onStoresLoaded.bind(me));
                    } else if (me.config.forceAjaxSearch && me.pendingSearch) {
                        me.executePendingSearch();
                    }
                });

            window.addEventListener('resize', me.onResize.bind(me));

            me.executeSearchFromURL();
        },
        computed: {
            filteredStores() {
                const stores = [];

                this.stores.filter(s => s.showAlways === 'top').forEach(s => stores.push(s));

                this.stores.filter(s => s.filtered === false && s.showAlways === 'no').sort((a, b) => {
                    return this.searchFilter.orderBy.direction === 'asc'
                        ? this.sortFunction(a, b)
                        : this.sortFunction(b, a);
                }).forEach(s => stores.push(s));

                this.stores.filter(s => s.showAlways === 'bottom').forEach(s => stores.push(s));

                return stores.slice(0, this.config.searchResultLimit || 100);
            },
            geoLocationEnabled() {
                return this.map && this.map.isGeoLocationEnabled();
            },
            selectedCountry() {
                return this.countries.find(c => c.id === this.searchFilter.countryId);
            },
            sortFunction() {
                const sortFunctions = {
                    distance: (a, b) => {
                        return a.distance - b.distance;
                    },
                    country: (a, b) => {
                        a = a.countryId + a.zipCode;
                        b = b.countryId + b.zipCode;

                        return a.localeCompare(b);
                    },
                    name: (a, b) => {
                        return a.label.localeCompare(b.label);
                    },
                    random: () => {
                        return Math.round(Math.random() * 10) >= 5 ? 1 : -1;
                    }
                };

                return sortFunctions[this.searchFilter.orderBy.id] || sortFunctions.distance;
            },
            autocompletedStores() {
                const fields = this.config.searchKeywordEntityFields || [ 'label' ];

                return this.stores.filter(store => {
                    const search = this.searchInput.toLowerCase();

                    for (let i = 0; i < fields.length; i++) {
                        const field = fields[i];
                        const value = store[field] || '';

                        if (value.toLowerCase().indexOf(search) > -1) {
                            return true;
                        }
                    }

                    return store.featured;
                }).slice(0, 100);
            },
            filterClass() {
                const classList = ['filter-container'];

                if (!this.filter.offCanvas) {
                    classList.push('container');

                    if (!this.filter.opened) {
                        classList.push('d-none');
                    }
                }

                return classList;
            },
            autocompleteEnabled() {
                return this.config.disableAutocomplete !== true;
            },
            filteredCountries() {
                if (this.countryFilterInput.length > 0) {
                    return this.countries.filter(c => {
                        return c.isoCode.toLowerCase().indexOf(this.countryFilterInput.toLowerCase()) > -1
                            || c.label.toLowerCase().indexOf(this.countryFilterInput.toLowerCase()) > -1;
                    });
                }

                return this.countries;
            },
            mapOverlayHeight() {
                if (this.$refs.mapOverlay) {
                    return this.$refs.mapOverlay.offsetHeight;
                }

                return 0;
            },
            filterOffCanvasOpened() {
                return this.filter.opened && this.filter.offCanvas;
            },
            searchResultShouldBeHidden() {
                return true === this.config.searchKeywordRequired && '' === this.searchInput;
            },
            isToggleFilterButtonVisible() {
                return this.config.filterShowAlways !== true || this.filter.offCanvas;
            },
            storesQuery() {
                if (this.config.forceAjaxSearch && this.currentLocation) {
                    let { lat, lng } = this.currentLocation.geometry.location;

                    return {
                        radius: this.searchFilter.radius,
                        lat: lat(),
                        lng: lng()
                    }
                }

                return null;
            },
            hasNewStoresQuery() {
                return JSON.stringify(this.storesQuery) !== JSON.stringify(this.lastStoresQuery);
            }
        },
        watch: {
            currentLocation() {
                this.calculateDistances();
                this.map.toggleLocalMarker(this.currentLocation);
            },
            searchInput() {
                if (this.searchInputFocused && this.autocompleteEnabled) {
                    clearTimeout(this.searchInputTimeout);

                    this.searchInputTimeout = setTimeout(() => {
                        this.loadAutocompleteResults();
                    }, 100);
                }

                this.updateQueryParameters();
            },
            /**
             * We need to create a new session token each time the search field is focused.
             * The session token is also regenerated when we call `this.map.loadPlace`
             *
             * @see https://developers.google.com/maps/documentation/javascript/places-autocomplete
             */
            searchInputFocused() {
                if (this.searchInputFocused && this.autocompleteEnabled) {
                    this.map.createAutocompleteSessionToken();
                    this.loadAutocompleteResults();
                }
            },
            searchFilter: {
                deep: true,
                handler() {
                    this.updateQueryParameters();
                }
            },
        },
        methods: {
            async loadConfig() {
                const me = this;

                me.config                  = JSON.parse(me.$refs.config.value);
                me.config.storeUrl         = me.$refs.storeUrl.value;
                me.config.getStoresUrl     = me.$refs.getStoresUrl.value;
                me.config.selectStoreUrl   = me.$refs.selectStoreUrl ? me.$refs.selectStoreUrl.value : null;
                me.config.orderTypes       = JSON.parse(me.$refs.orderTypes.value);
                me.config.customFilters    = JSON.parse(me.$refs.filters.value);
                me.config.localMarkerTitle = me.$refs.localMarkerTitle.value;
                me.countries               = JSON.parse(me.$refs.countries.value);

                if (me.config.forceAjaxSearch) {
                    me.config.autoloadStores        = false;
                    me.config.searchKeywordRequired = true;
                }
            },

            log(...args) {
                if (!this.logEnabled) {
                    return;
                }

                console.log('[store-locator]', ...args);
            },
            loadStores() {
                let url = this.config.getStoresUrl;

                if (this.storesQuery) {
                    url += '?radius=' + this.storesQuery.radius;
                    url += '&lat=' + this.storesQuery.lat;
                    url += '&lng=' + this.storesQuery.lng;

                    this.lastStoresQuery = this.storesQuery;
                }

                return this.httpClient.get(url)
                    .then(response => response.data);
            },
            showFiltersIfRequired() {
                if (
                    !this.config.autoloadStores && false === this.config.showIndividualFiltersInOffCanvas
                    || this.config.filterShowAlways === true && !(
                        this.filterService.hasCustomFilters() && this.config.showIndividualFiltersInOffCanvas === true
                        || this.isMobile
                    )
                ) {
                    this.filter.opened = true;
                }
            },
            onResize() {
                const me = this;

                me.isMobile = !matchMedia('screen and (min-width: 1024px)').matches;

                if (me.isMobile) {
                    const mapHeight = Math.min(window.innerHeight, window.outerHeight) - this.mapOverlayHeight;

                    me.map.$map.style.height                    = mapHeight + 'px';
                    me.$refs.storeContainerWrapper.style.height = mapHeight + 'px';

                    me.map.map.setOptions({ zoomControl: false });
                } else {
                    me.map.$map.style.height = '80vh';
                    me.map.map.setOptions({ zoomControl: true });

                    me.showFiltersIfRequired();
                }

                me.filter.offCanvas = me.isMobile || this.filterService.hasCustomFilters() && this.config.showIndividualFiltersInOffCanvas === true;

                me.log('onResize');
            },
            onStoresLoaded(stores) {
                const me = this;

                me.log('stores loaded', stores);

                stores.forEach(store => {
                    store.distance = null;
                    store.filtered = false;

                    me.map.initStore(store);
                });

                me.isLoadingStores = false;
                me.stores          = stores;

                on('netiStoreLocator.storeMarker.click', ({ store, focus }) => {
                    me.selectedStore = store;

                    if (focus) {
                        me.map.showInfoWindow(store);
                        me.map.focusStore(store);
                    }
                });

                on('netiStoreLocator.store.select', ({ store }) => {
                    me.setStoreSelectSelection(store);
                });

                if (me.pendingSearch) {
                    me.executePendingSearch();
                } else {
                    me.calculateDistances();
                    me.onSearch();
                    me.onResize();
                }

                me.initStoreSelectListener();
            },
            onSelectCountry(country) {
                const me = this;

                me.searchFilter.countryId = country.id;

                if (true === this.config.clearSearchOnCountryChange) {
                    this.searchInput     = '';
                    this.currentLocation = null;
                }

                me.setCountriesBySelectedCountry();
                me.focusCountryIfNeeded();

                if (me.config.autoloadStores || me.storesWereLoadedOnce) {
                    me.$nextTick(() => {
                        me.onSearch();
                    });
                }
            },
            onStoreClicked(store) {
                const me = this;

                me.searchInputFocused = false;
                me.selectedStore      = store;
                me.searchInput        = store.label;

                me.map.showInfoWindow(store);

                if (typeof store.googlePlaceID === 'string' && store.googlePlaceID.length > 0) {
                    me.map.loadPlace(store.googlePlaceID).then(data => {
                        me.log('Loaded place for clicked store', store, data);

                        me.currentLocation = data;

                        me.$nextTick(() => {
                            me.onSearch();

                            me.map.focusStore(store);
                            emit('netiStoreLocator.storeMarker.click', { store });
                        })
                    })
                } else {
                    me.map.focusStore(store);
                    store.marker.show();

                    emit('netiStoreLocator.storeMarker.click', { store });
                }
            },
            onPlaceClicked(place) {
                const me = this;

                me.map.loadPlace(place.place_id).then(data => {
                    me.searchInput     = place.description;
                    me.currentLocation = data;

                    me.$nextTick(() => {
                        me.onSearch();

                        scrollTo(0, 500, me.$refs.storesContainer);
                    });
                });
            },
            /**
             * The search result box must be shown 150 ms after the search input loses focus, otherwise the
             * @click-Handler will not work for .search-result items.
             */
            onBlur() {
                const me = this;

                setTimeout(() => me.searchInputFocused = false, 150);
            },
            loadAutocompleteResults() {
                if (!this.searchInput) {
                    return;
                }

                this.map.loadPlacePredictions(this.searchInput).then(result => {
                    this.autocompleteResults = result;
                });
            },
            /**
             * Tries to retrieve the coordinates of the browser, zoom into that location and execute the search based
             * on the location.
             */
            onLocate() {
                const me = this;

                if (me.preventLocate) {
                    me.preventLocate = false;

                    return;
                }

                me.log('onLocate');

                const promise = me.map.doLocate();

                if (promise) {
                    me.isLocating = true;

                    promise.then(({ result, address }) => {

                        me.isLocating = false;

                        me.log('onLocate success', result, address);

                        me.searchInput     = address;
                        me.currentLocation = result;

                        if (me.autoloadStores || me.storesWereLoadedOnce) {
                            me.$nextTick(() => {
                                me.onSearch();

                                scrollTo(0, 500, me.$refs.storesContainer);
                            });
                        }
                    });

                    promise.catch(() => {
                        me.isLocating = false;
                    });
                }
            },
            onToggleFilter() {
                const me = this;

                me.filter.opened = !me.filter.opened;
            },
            onToggleStores() {
                const me = this;

                me.storeListing = !me.storeListing;
                emit('netiStoreLocator.toggleStores', me.storeListing);
            },
            onMapLoaded() {
                const me = this;

                me.log('map loaded');

                me.onLocate();
                me.setCountriesBySelectedCountry();
                me.onResize();
                me.focusCountryIfNeeded();
            },
            onReset() {
                const me = this;

                me.searchFilter.countryId = me.countries[0].id;
                me.searchInput            = '';
                me.currentLocation        = null;

                me.filterService.reset();

                me.log('onReset');
            },
            onAutocompletedSearch() {
                const me    = this;

                if ('' === this.searchInput) {
                    return;
                }

                const place = me.autocompleteResults[0] || null;
                const store = me.autocompletedStores[0] || null;

                if (place) {
                    this.onPlaceClicked(place);
                } else if (store) {
                    this.onStoreClicked(store);
                } else {
                    me.log('Nothing found kinda.');
                }
            },
            onSearch(e) {
                const me     = this;
                const stores = [];

                if (e !== undefined) {
                    me.filterService.resetAfterSearch();
                }

                if (me.filter.offCanvas) {
                    me.filter.opened = false;
                }

                if (e && !me.stores.length && me.config.autoloadStores !== true || me.hasNewStoresQuery) {
                    me.isLoadingStores      = true;
                    me.storesWereLoadedOnce = true;
                    me.loadStores().then(me.onStoresLoaded.bind(me));

                    return;
                }

                me.map.clearActiveMarkers();
                me.stores.forEach(store => {
                    if (
                        (
                            !store.featured || me.config.restrictFeaturedStoresToCountry
                        )
                        && me.searchFilter.countryId
                        && me.searchFilter.countryId !== 'all'
                        && store.countryId !== me.searchFilter.countryId
                    ) {
                        store.filtered = true;
                    }

                    // Filter stores by radius (distance is calculated in realtime)
                    else if (
                        (!store.featured || true === this.config.filterHighlightedStoresByRadiusFirst)
                        && me.searchFilter.radius
                        && store.distance > parseInt(me.searchFilter.radius)
                        && store.showAlways === 'no'
                    ) {
                        store.filtered = true;
                    } else {
                        store.filtered = false;
                    }

                    const match = me.filterService.match(store);

                    if (null !== match) {
                        store.filtered = match === false;
                    }

                    if (
                        true === me.searchFilter.onlyFeatured
                        && false === store.filtered
                        && true !== store.featured
                    ) {
                        store.filtered = true;
                    }

                    if (this.searchResultShouldBeHidden) {
                        store.filtered = true;
                    }

                    const isShownAlways = store.showAlways === 'top' || store.showAlways === 'bottom';

                    if (store.filtered && false === isShownAlways) {
                        store.marker.hide();
                    } else {
                        stores.push(store);
                    }
                });

                // Ensure that only the stores from the "filteredStores" list are shown (because of the max stores limit)
                const bounds   = new window.google.maps.LatLngBounds();
                let boundsSize = 0;

                this.filteredStores.forEach(store => {
                    store.marker.show();

                    bounds.extend(
                        new window.google.maps.LatLng({
                            lat: store.latitude,
                            lng: store.longitude
                        })
                    );

                    ++boundsSize;
                });

                if (true === this.config.focusLocalMarker && this.currentLocation && this.map.localMarker) {
                    const position = this.currentLocation.geometry.location;

                    bounds.extend(
                        new window.google.maps.LatLng({
                            lat: position.lat(),
                            lng: position.lng()
                        })
                    );

                    ++boundsSize;
                }

                // Focus the map on the search result(s)
                if (me.searchFilter.storeId !== null) {
                    const store = me.stores.find(s => s.id === me.searchFilter.storeId);

                    me.onStoreClicked(store);
                } else if (stores.length === 1 && boundsSize === 1) {
                    const store = stores[0];

                    me.map.focusStore(store);
                } else if (!bounds.isEmpty()) {
                    me.map.map.fitBounds(bounds);
                }

                me.map.enableActiveMarkers();

                me.log('onSearch');
            },
            calculateDistances() {
                const me = this;

                me.log('calculateDistances');

                me.stores.forEach(store => {
                    if (me.currentLocation === null) {
                        store.distance = null;
                    } else {
                        store.distance = distanceBetweenPoints(
                            new window.google.maps.LatLng({
                                lat: store.latitude,
                                lng: store.longitude
                            }),
                            this.currentLocation.geometry.location,
                            this.config.distanceUnit
                        );
                    }
                });
            },
            executeSearchFromURL() {
                const me  = this;
                const url = new URL(window.location);

                me.log('execute search from url');

                if (url.searchParams.has('search')) {
                    // Apply "searchInput" from query parameter "search" and preserve search
                    me.searchInput   = url.searchParams.get('search');
                    me.preventLocate = true;
                    me.pendingSearch = true;
                }

                me.filterService.load(
                    url,
                    {
                        config: me.config,
                        pendingSearch() {
                            me.preventLocate = true;
                            me.pendingSearch = true;
                        }
                    }
                );

                const country = url.searchParams.get('country') || null;

                // Apply "country" from query parameter "country"
                if (country && me.countries.length > 2) {
                    const selectedCountry = me.countries.find(c => c.id !== 'all' && c.isoCode === country);

                    if (selectedCountry) {
                        me.searchFilter.countryId = selectedCountry.id;
                    } else {
                        console.log('Warning: The given country "%s" is not available.', country);
                    }
                } else {
                    const defaultCountry = me.countries.find(c => c.default === true);

                    if (defaultCountry) {
                        me.searchFilter.countryId = defaultCountry.id;
                    }
                }
            },
            executePendingSearch() {
                const me = this;

                me.log('execute pending search');

                me.pendingSearch = false;

                if (0 === me.searchInput.length) {
                    me.calculateDistances();
                    me.setCountriesBySelectedCountry();
                    me.onSearch();

                    return;
                }

                me.map.loadPlacePredictions(me.searchInput).then(result => {
                    if (!result.length) {
                        // No results found
                        return;
                    }

                    me.map.geoCode({ address: result[0].description }).then(result => {
                        me.searchInput     = result.formatted_address;
                        me.currentLocation = result;

                        me.calculateDistances();
                        me.setCountriesBySelectedCountry();
                        me.onSearch();
                    }).catch(error => {
                        // No results found
                    });
                }).catch(error => {
                    // No results found
                });
            },
            setCountriesBySelectedCountry() {
                const me = this;

                if (me.searchFilter.countryId === 'all') {
                    me.map.setCountries(
                        me.countries.filter(c => c.id !== 'all').map(c => c.isoCode)
                    );
                } else {
                    me.map.setCountries(
                        me.countries.filter(c => c.id === me.searchFilter.countryId).map(c => c.isoCode)
                    );
                }
            },
            updateQueryParameters() {
                const me  = this;
                const url = new URL(window.location);

                me.log('update query parameters');

                if (me.searchInput.length > 0) {
                    url.searchParams.set('search', me.searchInput);
                } else {
                    url.searchParams.delete('search');
                }

                const selectedCountry = me.countries.find(c => c.id === me.searchFilter.countryId);

                if (selectedCountry && selectedCountry.id !== 'all') {
                    url.searchParams.set('country', selectedCountry.isoCode);
                } else {
                    url.searchParams.delete('country');
                }

                me.filterService.save(url);

                window.history.pushState({}, '', url);
            },
            initStoreSelectListener() {
                if (this.config._storePickupEnabled !== true) {
                    return;
                }

                const storeSearchPluginList = window.PluginManager.getPluginInstances('NetiStoreSearch');

                storeSearchPluginList.forEach(plugin => {
                    plugin.$emitter.subscribe('onStoreSelected', (event) => {
                        const { detail } = event;
                        const storeId    = detail.id || null;

                        if (storeId !== null) {
                            this.setSelectedStore(storeId);
                        }
                    });
                });
            },
            setStoreSelectSelection(store) {
                const data = JSON.stringify({ storeId: store.id });

                this.httpClient.post(this.config.selectStoreUrl, data)
                    .then(response => JSON.parse(response))
                    .then(({ data: store }) => {
                        this.setSelectedStore(store.id);

                        // Forward changed store to store pickup plugins
                        const storeSearchPluginList = window.PluginManager.getPluginInstances('NetiStoreSearch');
                        let storeSearchPlugin       = null;

                        storeSearchPluginList.forEach(plugin => {
                            if (!storeSearchPlugin) {
                                plugin.$emitter.publish('onStoreSelected', { id: store.id });
                                storeSearchPlugin = plugin;
                            }
                        });
                    });
            },
            setSelectedStore(storeId) {
                const store         = this.stores.find(s => s.id === storeId);
                const selectedStore = this.stores.find(store => {
                    return typeof store.extensions.netiStorePickupSelected === 'object'
                        && store.extensions.netiStorePickupSelected !== null;
                });

                if (selectedStore) {
                    this.$set(selectedStore.extensions, 'netiStorePickupSelected', null);
                }

                if (store) {
                    this.$set(store.extensions, 'netiStorePickupSelected', {});
                }
            },
            onOffcanvasOpened() {
                this.emitter.emit('offcanvas-opened');
            },
            onOffcanvasClosed() {
                this.emitter.emit('offcanvas-closed');
            },
            focusCountryIfNeeded() {
                if (
                    true === this.config.autoloadStores
                    || this.storesWereLoadedOnce
                ) {
                    return;
                }

                const country = this.countries.find(c => c.id === this.searchFilter.countryId);

                if (country && country.id !== 'all') {
                    this.map.geoCode({ address: country.label }).then(result => {
                        this.map.map.setCenter(result.geometry.location);
                        this.map.map.fitBounds(result.geometry.bounds);
                    });
                } else {
                    this.map.map.setCenter({ lat: 0, lng: 0 });
                    this.map.map.setZoom(3);
                }
            }
        }
    };
}