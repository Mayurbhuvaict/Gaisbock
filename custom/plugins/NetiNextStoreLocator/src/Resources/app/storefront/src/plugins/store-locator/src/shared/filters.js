/**
 * Filters
 */
const filters = [
    {
        name: 'distance',
        load(url, filter, { pendingSearch, config }) {
            const distance = url.searchParams.get('distance') || 0;

            // Apply "radius" from query parameter "distance"
            if (parseInt(distance) > 0 && config.searchRadiusValues.split(';').indexOf(distance) > -1) {
                filter.radius = parseInt(distance);
            }
        },
        save(url, filter) {
            url.searchParams.set('distance', filter.radius);
        },
        reset(filter, config) {
            filter.radius = config.defaultSearchRadius;
        },
        match(store, filter) {
            return null;
        }
    },
    {
        name: 'order',
        load(url, filter, { pendingSearch, config }) {
            const order = url.searchParams.get('order') || null;

            // Apply "orderBy" from query parameter "order"
            if (order && config.orderTypes.indexOf(order) > -1) {
                filter.orderBy.id = order;
            }
        },
        save(url, filter) {
            url.searchParams.set('order', filter.orderBy.id);
        },
        reset(filter, config) {
            filter.orderBy.id        = 'distance';
            filter.orderBy.direction = 'asc';
        },
        match(store, filter) {
            return null;
        }
    },
    {
        name: 'storeId',
        resetAfterSearch: true,

        /**
         * This method is called when the URL is getting parsed to retrieve search filters
         *
         * @param url
         * @param filter
         * @param pendingSearch
         */
        load(url, filter, { pendingSearch }) {
            if (url.searchParams.has('storeId')) {
                filter.storeId = url.searchParams.get('storeId').toLowerCase();

                pendingSearch();
            }
        },

        /**
         * When the URL query parameters should be updated by the existing search filters
         *
         * @param url
         * @param filter
         */
        save(url, filter) {
            if (filter.storeId !== null) {
                url.searchParams.set('storeId', filter.storeId);
            } else {
                url.searchParams.delete('storeId');
            }
        },

        /**
         * When the filter value should be set to default
         *
         * @param filter
         */
        reset(filter) {
            filter.storeId = null;
        },

        /**
         * Whether the filter is matching the store or not
         *
         * @param store
         * @param filter
         * @returns {null|boolean}
         */
        match(store, filter) {
            if (filter.storeId !== null) {
                return store.id.toLowerCase() === filter.storeId;
            }

            return null;
        }
    },
    {
        name: 'city',
        resetAfterSearch: true,
        load(url, filter, { pendingSearch }) {
            if (url.searchParams.has('city')) {
                filter.city = url.searchParams.get('city').split(',').map(s => s.toLowerCase());

                pendingSearch();
            }
        },
        save(url, filter) {
            if (filter.city !== null) {
                url.searchParams.set('city', filter.city.join(','));
            } else {
                url.searchParams.delete('city');
            }
        },
        reset(filter) {
            filter.city = null;
        },
        match(store, filter) {
            if (
                filter.city !== null
            ) {
                return filter.city.indexOf(store.city.toLowerCase()) !== -1;
            }

            return null;
        }
    },
    {
        name: 'tag',
        resetAfterSearch: true,
        load(url, filter, { pendingSearch }) {
            if (url.searchParams.has('tag')) {
                filter.tag = url.searchParams.get('tag').split(',').map(s => s.toLowerCase());

                pendingSearch();
            }
        },
        save(url, searchFilter) {
            if (searchFilter.tag !== null && searchFilter.tag.length > 0) {
                url.searchParams.set('tag', searchFilter.tag.join(','));
            } else {
                url.searchParams.delete('tag');
            }
        },
        reset(filter, config, customFilters) {
            // Remove tags from list which are not available through custom filters
            if (filter.tag !== null && customFilters.length > 0) {
                const tagWhitelist = [];

                customFilters.forEach(filter => {
                    if (filter.valueType !== 1) {
                        return;
                    }

                    filter.values.forEach(filterValue => {
                        tagWhitelist.push(filterValue.label.toLowerCase());
                    });
                });

                for (let i = filter.tag.length - 1, tag; i >= 0, tag = filter.tag[i]; i--) {
                    if (tagWhitelist.findIndex(t => t === tag) < 0) {
                        filter.tag.splice(i, 1);
                    }
                }

                return;
            }

            filter.tag = null;
        },
        match(store, filter, config) {
            if (filter.tag !== null && filter.tag.length > 0) {
                const tagMode = config.filterTagMode || 'one';
                const matches = store.tags.filter(tag => filter.tag.indexOf(tag.name.toLowerCase()) > -1).length;

                if (!matches || tagMode === 'all' && matches !== filter.tag.length) {
                    return false;
                }
            }

            return null;
        }
    },
    {
        name: 'storeName',
        resetAfterSearch: true,
        load(url, filter, { pendingSearch }) {
            if (url.searchParams.has('storeName')) {
                filter.storeName = url.searchParams.get('storeName').toLowerCase();
                pendingSearch();
            }
        },
        save(url, filter) {
            if (filter.storeName !== null) {
                url.searchParams.set('storeName', filter.storeName);
            } else {
                url.searchParams.delete('storeName');
            }
        },
        reset(filter) {
            filter.storeName = null;
        },
        match(store, filter) {
            if (filter.storeName !== null) {
                return store.label.toLowerCase().indexOf(filter.storeName) !== -1;
            }

            return null;
        }
    },
    {
        name: 'customFields',
        resetAfterSearch: true,
        load(url, filter, { pendingSearch, config, reactiveSet }) {
            url.searchParams.forEach((value, key) => {
                if (key.indexOf('customFields[') === 0) {
                    /**
                     * In some cases, specially when Shopware does it's magic, it happens that the ] character is
                     * actually removed from the URL so we needed to adjust the following code. This happens on a
                     * language switch.
                     *
                     * @type {string}
                     */
                    key = key.slice(13, key[key.length - 1] === ']' ? -1 : undefined);

                    filter.customFields = filter.customFields || {};

                    if (key in filter.customFields) {
                        filter.customFields[key].push(value);
                    } else {
                        reactiveSet(filter.customFields, key, [value]);
                    }

                    pendingSearch();
                }
            });
        },
        save(url, filter) {
            url.searchParams.forEach((value, key) => {
                if (key.indexOf('customFields[') === 0) {
                    url.searchParams.delete(key);
                }
            });

            if (filter.customFields !== null) {
                for (let key in filter.customFields) {
                    if (!filter.customFields.hasOwnProperty(key)) {
                        continue;
                    }

                    filter.customFields[key].forEach(value => {
                        url.searchParams.append(
                            'customFields[' + key + ']',
                            value
                        );
                    });
                }
            }
        },
        reset(filter, config, customFilters) {
            if (filter.customFields !== null && customFilters.length > 0) {
                const customFieldWhitelist = {};

                customFilters.forEach(filter => {
                    if (filter.valueType !== 2) {
                        return;
                    }

                    customFieldWhitelist[filter.customFieldName] = [];

                    filter.values.forEach(filterValue => {
                        customFieldWhitelist[filter.customFieldName].push(filterValue.value);
                    });
                });

                for (let key in filter.customFields) {
                    if (!filter.customFields.hasOwnProperty(key)) {
                        continue;
                    }

                    const values = filter.customFields[key];

                    if (!(
                        key in customFieldWhitelist
                    )) {
                        filter.customFields[key].splice(0, filter.customFields[key].length);
                        continue;
                    }

                    for (let i = values.length - 1, value; i >= 0, value = values[i]; i--) {
                        if (customFieldWhitelist[key].findIndex(v => v === value) < 0) {
                            values.splice(i, 1);
                        }
                    }
                }

                return;
            }

            filter.customFields = {};
        },
        match(store, filter) {
            if (null !== filter.customFields) {
                if (null === store.customFields) {
                    store.customFields = {};
                }

                for (let key in filter.customFields) {
                    if (!filter.customFields.hasOwnProperty(key)) {
                        continue;
                    }

                    let originalValue = store.customFields[key];
                    let values        = filter.customFields[key];

                    for (let i = 0, value; i < values.length, value = values[i]; i++) {
                        if (value === 'true' && originalValue === true) {
                            value = true;
                        }

                        if (value === 'false' && originalValue === false) {
                            value = false;
                        }

                        if (value === 'false' && !originalValue) {
                            value         = false;
                            originalValue = false;
                        }

                        if (Array.isArray(originalValue)) {
                            if (originalValue.indexOf(value) === -1) {
                                return false;
                            }
                        } else if (originalValue !== value) {
                            return false;
                        }
                    }
                }
            }

            return null;
        }
    },
    {
        name: 'onlyFeatured',
        load(url, filter, { pendingSearch, config }) {
            let featured = url.searchParams.get('featured') || 0;

            featured            = parseInt(featured) === 1;
            filter.onlyFeatured = featured;
        },
        save(url, filter) {
            if (filter.onlyFeatured) {
                url.searchParams.set('featured', '1');
            } else {
                url.searchParams.delete('featured');
            }
        },
        reset(filter, config) {
            filter.onlyFeatured = false;
        },
        match(store, filter) {
            return null;
        }
    },
];

export default class FilterService {
    constructor(searchFilter, config, reactiveSet) {
        this.logEnabled    = 'development' === process.env.NODE_ENV;
        this.searchFilter  = searchFilter;
        this.config        = config;
        this.customFilters = config.customFilters;
        this.reactiveSet   = reactiveSet;
    }

    log(...args) {
        if (!this.logEnabled) {
            return;
        }

        console.log('[store-locator::filter-service]', ...args);
    }

    load(url, options) {
        options.reactiveSet = this.reactiveSet;

        filters.forEach(f => f.load(url, this.searchFilter, options));

        this.initializeCustomFilters();
    }

    save(url) {
        filters.forEach(f => f.save(url, this.searchFilter));
    }

    reset() {
        filters.forEach(f => f.reset(this.searchFilter, this.config, this.customFilters));

        this.resetCustomFilters();
    }

    resetAfterSearch() {
        filters.forEach(f => {
            if (f.resetAfterSearch === true) {
                f.reset(this.searchFilter, this.config, this.customFilters);

                this.log('reset filter:', f.name);
            }
        });
    }

    match(store) {
        let isMatch = null;

        filters.forEach(f => {
            const match = f.match(store, this.searchFilter, this.config);

            if (null !== match) {
                isMatch = match;
            }
        });

        return isMatch;
    }

    resetCustomFilters() {
        this.log('reset custom filters');

        this.customFilters.forEach(filter => {
            if (!(
                filter.id in this.searchFilter.customFilters
            )) {
                return;
            }

            switch (filter.displayType) {
                case 1: // Checkbox
                    filter.values.forEach(filterValue => {
                        if (filterValue.id in this.searchFilter.customFilters[filter.id]) {
                            this.searchFilter.customFilters[filter.id][filterValue.id] = false;
                        }
                    });
                    break;
                case 2: // Radio
                case 3: // Select
                    this.searchFilter.customFilters[filter.id] = '';
                    break;
            }
        });

        this.searchFilter.customFilterCount = 0;

        // This is a hack used to update the input field values (because the v-model is not reactive as vue wants)
        this.searchFilter.customFilterVisible = false;
        setTimeout(() => this.searchFilter.customFilterVisible = true, 0);
    }

    hasCustomFilters() {
        return this.customFilters.length > 0;
    }

    /**
     * Welcome to the complexity world of custom filters.
     */
    initializeCustomFilters() {
        const me = this;

        this.customFilters.forEach(filter => {
            const { id: filterId } = filter;

            switch (filter.displayType) {
                case 1: // Checkbox
                    this.searchFilter.customFilters[filterId] = this.searchFilter.customFilters[filterId] || {};

                    filter.values.forEach(value => {
                        let checked = false;
                        let name    = value.label.toLowerCase();

                        if (filter.valueType === 1) {
                            if (this.searchFilter.tag !== null) {
                                const index = this.searchFilter.tag.findIndex(t => t === name);

                                if (index >= 0) {
                                    checked = true;
                                    ++me.searchFilter.customFilterCount;
                                }
                            }

                            Object.defineProperty(
                                this.searchFilter.customFilters[filterId],
                                value.id,
                                {
                                    configurable: true,
                                    get() {
                                        return checked;
                                    },
                                    set(propertyValue) {
                                        checked = propertyValue;

                                        if (true === checked) {
                                            me.addTag(name);
                                            ++me.searchFilter.customFilterCount;
                                        } else {
                                            me.removeTag(name);
                                            --me.searchFilter.customFilterCount;
                                        }
                                    }
                                }
                            );
                        } else if (filter.valueType === 2) {
                            let customFieldValue = me.searchFilter.customFields[filter.customFieldName];

                            if (customFieldValue) {
                                const index = customFieldValue.findIndex(v => v === value.value);

                                if (index >= 0) {
                                    checked = true;
                                    ++me.searchFilter.customFilterCount;
                                }
                            }

                            Object.defineProperty(
                                this.searchFilter.customFilters[filterId],
                                value.id,
                                {
                                    configurable: true,
                                    get() {
                                        return checked;
                                    },
                                    set(propertyValue) {
                                        checked = propertyValue;

                                        if (checked) {
                                            me.addCustomFieldValue(filter.customFieldName, value.value);
                                            ++me.searchFilter.customFilterCount;
                                        } else {
                                            me.removeCustomFieldValue(filter.customFieldName, value.value);
                                            --me.searchFilter.customFilterCount;
                                        }
                                    }
                                }
                            );
                        }

                    });

                    break;
                case 2: // Radio
                case 3: // Select
                    if (filter.valueType === 1) {
                        let tagId        = '';
                        const tagMapping = {};

                        filter.values.forEach(value => {
                            tagMapping[value.id] = value.label.toLowerCase();

                            // Load tag from url
                            if (this.searchFilter.tag !== null) {
                                const index = this.searchFilter.tag.findIndex(t => t === tagMapping[value.id]);

                                if (index >= 0) {
                                    tagId = value.id;
                                    ++me.searchFilter.customFilterCount;
                                }
                            }
                        });

                        Object.defineProperty(
                            this.searchFilter.customFilters,
                            filterId,
                            {
                                configurable: true,
                                get() {
                                    return tagId;
                                },
                                set(selectedTagId) {
                                    const tagName = tagMapping[selectedTagId];

                                    if (tagId !== '') {
                                        me.removeTag(tagMapping[tagId]);
                                    }

                                    if (tagName) {
                                        me.addTag(tagName);
                                        tagId = selectedTagId;
                                        ++me.searchFilter.customFilterCount;
                                    } else {
                                        tagId = '';
                                        --me.searchFilter.customFilterCount;
                                    }
                                }
                            }
                        );
                    } else if (filter.valueType === 2) {
                        let selectedValue      = '';
                        let customFieldValue   = me.searchFilter.customFields[filter.customFieldName];
                        let customFieldMapping = {};

                        filter.values.forEach(value => {
                            if (customFieldValue) {
                                const index = customFieldValue.findIndex(v => v === value.value);

                                if (index >= 0) {
                                    selectedValue = value.id;
                                    ++me.searchFilter.customFilterCount;
                                }
                            }

                            customFieldMapping[value.id] = value.value;
                        });

                        Object.defineProperty(
                            this.searchFilter.customFilters,
                            filterId,
                            {
                                configurable: true,
                                get() {
                                    return selectedValue;
                                },
                                set(selectedValueId) {
                                    let customFieldName = customFieldMapping[selectedValueId];

                                    if (selectedValue !== '') {
                                        me.removeCustomFieldValue(
                                            filter.customFieldName,
                                            customFieldMapping[selectedValue]
                                        );
                                    }

                                    if (customFieldName) {
                                        me.addCustomFieldValue(filter.customFieldName, customFieldName);

                                        selectedValue = selectedValueId;
                                        ++me.searchFilter.customFilterCount;
                                    } else {
                                        selectedValue = '';
                                        --me.searchFilter.customFilterCount;
                                    }
                                }
                            }
                        );
                    }

                    break;
            }
        });
    }

    addCustomFieldValue(customFieldName, value) {
        this.removeCustomFieldValue(customFieldName, value);

        let customFieldValue = this.searchFilter.customFields[customFieldName];

        if (!customFieldValue) {
            this.reactiveSet(this.searchFilter.customFields, customFieldName, []);

            customFieldValue = this.searchFilter.customFields[customFieldName];
        }

        customFieldValue.push(value);
    }

    removeCustomFieldValue(customFieldName, value) {
        let customFieldValue = this.searchFilter.customFields[customFieldName];

        if (customFieldValue) {
            const index = customFieldValue.findIndex(v => v === value);

            if (index >= 0) {
                customFieldValue.splice(index, 1);
            }
        }
    }

    removeTag(name) {
        if (this.searchFilter.tag !== null) {
            const index = this.searchFilter.tag.findIndex(t => t === name);

            if (index >= 0) {
                this.searchFilter.tag.splice(index, 1);
            }
        }
    }

    addTag(name) {
        if (this.searchFilter.tag === null) {
            this.searchFilter.tag = [];
        }

        this.removeTag(name);
        this.searchFilter.tag.push(name);
    }
}