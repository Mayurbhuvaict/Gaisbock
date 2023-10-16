import template from './template.twig';
import errorConfig from './error-config.json';
import './tabs/general';
import './tabs/address';
import './tabs/details';
import './tabs/contact';
import './tabs/business-hours';

const { Component, Mixin } = Shopware;
const { Criteria }         = Shopware.Data;
const { mapPageErrors }    = Shopware.Component.getComponentHelper();

Component.register('neti-store-locator-detail', {
    template,

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onAbortButtonClick'
    },

    data() {
        return {
            isLoading: false,
            store: null,
            businessHours: {},
            storeId: null,
            isSaveSuccessful: false,

            countries: [],
            salesChannels: [],
            weekDays: [],
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('neti_store_locator');
        },
        storeBusinessHourRepository() {
            return this.repositoryFactory.create('neti_store_business_hour');
        },
        businessHourRepository() {
            return this.repositoryFactory.create('neti_business_hour');
        },
        weekDayRepository() {
            return this.repositoryFactory.create('neti_business_weekday');
        },
        countryRepository() {
            return this.repositoryFactory.create('country');
        },
        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },
        storeCmsRepository() {
            return this.repositoryFactory.create('neti_store_cms');
        },
        isCreateMode() {
            return this.$route.name.includes('neti.store_locator.create');
        },
        defaultCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('country');
            criteria.addAssociation('countryState');
            criteria.addAssociation('salesChannels');
            criteria.addAssociation('tags');
            criteria.addAssociation('cmsPages.cmsPage');

            return criteria;
        },
        weekDayCriteria() {
            const criteria = new Criteria();

            criteria.addSorting(Criteria.sort('number', 'ASC'));

            return criteria;
        },

        storeBusinessHoursCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('storeId', this.storeId));
            criteria.addAssociation('businessHour');
            criteria.addAssociation('businessHour.translations');

            return criteria;
        },

        ...mapPageErrors(errorConfig)
    },

    created() {
        this.createdComponent();
    },

    watch: {
        '$route.params.id'() {
            this.createdComponent();
        }
    },

    methods: {
        createdComponent() {
            this.isLoading = true;

            if (this.weekDays.length !== 7) {
                this.getWeekDays();
            }

            if (this.isCreateMode) {
                this.store                   = this.repository.create();
                this.store.detailContentType = 'html';

                if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                    Shopware.State.commit('context/resetLanguageToDefault');
                }

                this.initializeFurtherComponents();
            } else {
                this.storeId = this.$route.params.id;

                this.repository.get(
                    this.storeId,
                    Shopware.Context.api,
                    this.defaultCriteria
                ).then((store) => {
                    this.store = store;
                    this.initializeFurtherComponents();
                    this.loadStoreBusinessHours();
                    this.sortFields();
                });
            }
        },

        abortOnLanguageChange() {
            return this.repository.hasChanges(this.store);
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.businessHours = {};
            this.createdComponent();
        },

        initializeFurtherComponents() {
            if (!this.store) {
                return;
            }

            this.isLoading = false;

            const criteria = new Criteria(1, 100);
            this.salesChannelRepository.search(criteria, Shopware.Context.api).then((searchResult) => {
                this.salesChannels = searchResult;
            });

            const countryCriteria = new Criteria(1, 500);
            countryCriteria.addSorting(Criteria.sort('name'));
            countryCriteria.addAssociation('states');

            this.countryRepository.search(countryCriteria, Shopware.Context.api).then((searchResult) => {
                this.countries = searchResult;
            });
        },

        saveFinish() {
            this.isSaveSuccessful = false;

            if (this.isCreateMode) {
                this.$router.push({
                    name: 'neti.store_locator.detail',
                    params: {
                        id: this.store.id
                    }
                });
            }
        },

        async onSave() {
            this.isSaveSuccessful = false;
            this.isLoading        = true;

            let hasErrors = this.validateBusinessHours();

            if (hasErrors) {
                this.isLoading = false;

                this.createNotificationError({
                    title: this.$t('neti-store-locator.detail.titleSaveError'),
                    message: this.$t('neti-store-locator.detail.messageBusinessTimeSaveError')
                });
                this.isLoading = false;

                return;
            }

            await this.setBusinessHours();

            this.businessHours = {};

            const { changes, deletionQueue } = this.repository.changesetGenerator.generate(this.store);

            if (changes && changes.cmsPages) {
                changes && changes.cmsPages.forEach( e => {
                    const storeCms = this.store.cmsPages.get(e.id);

                    if (null === storeCms) {
                        return;
                    }

                    e.cmsPageId = storeCms.cmsPageId;
                })
            }

            try {
                await this.repository.sendDeletions(deletionQueue, Shopware.Context.api);
                await this.repository.sendChanges(this.store, changes, Shopware.Context.api);

                this.isLoading        = false;
                this.isSaveSuccessful = true;

                if (!this.isCreateMode) {
                    this.createdComponent();
                }
            } catch (error) {
                this.createNotificationError({
                    title: this.$t('neti-store-locator.detail.titleSaveError'),
                    message: this.$t('neti-store-locator.detail.messageSaveError')
                });
                this.isLoading = false;
            }
        },

        onAbortButtonClick() {
            this.$router.push({ name: 'neti.store_locator.overview' });
            this.isLoading = false;
        },

        async setBusinessHours() {
            if (this.isCreateMode) {
                return;
            }

            await this.deleteOriginalHours();

            const promises = [];

            this.weekDays.forEach(weekDay => {
                if (undefined === this.businessHours[weekDay.id]) {
                    return;
                }

                this.businessHours[weekDay.id].forEach(businessHour => {
                    const businessHourEntity = this.businessHourRepository.create(Shopware.Context.api);
                    businessHourEntity.start = businessHour.start;
                    businessHourEntity.end   = businessHour.end;

                    const businessHourPromise = this.businessHourRepository.save(businessHourEntity, Shopware.Context.api).then(result => {
                        const savedBusinessHour       = JSON.parse(result.config.data);
                        const storeBusinessHourEntity = this.storeBusinessHourRepository.create(Shopware.Context.api);

                        storeBusinessHourEntity.businessWeekdayId = weekDay.id;
                        storeBusinessHourEntity.storeId           = this.store.id;
                        storeBusinessHourEntity.active            = true;
                        storeBusinessHourEntity.annual            = false;
                        storeBusinessHourEntity.businessHourId    = savedBusinessHour.id;

                        promises.push(this.storeBusinessHourRepository.save(storeBusinessHourEntity, Shopware.Context.api));
                    });

                    promises.push(businessHourPromise);
                });
            });

            if (undefined !== this.businessHours['openDays']) {
                this.businessHours['openDays'].forEach(openDay => {
                    const businessHourEntity       = this.businessHourRepository.create(Shopware.Context.api);
                    businessHourEntity.start       = openDay.start;
                    businessHourEntity.end         = openDay.end;
                    businessHourEntity.description = openDay.description;

                    const businessHourPromise = this.businessHourRepository.save(businessHourEntity, Shopware.Context.api).then(result => {
                        const savedBusinessHour       = JSON.parse(result.config.data);
                        const storeBusinessHourEntity = this.storeBusinessHourRepository.create(Shopware.Context.api);
                        let specialDate               = openDay.specialDate;

                        if (specialDate.includes('T', 0)) {
                            specialDate = openDay.specialDate.substring(0, openDay.specialDate.indexOf('T'));
                        }

                        storeBusinessHourEntity.storeId        = this.store.id;
                        storeBusinessHourEntity.active         = true;
                        storeBusinessHourEntity.annual         = openDay.annual;
                        storeBusinessHourEntity.specialDate    = specialDate;
                        storeBusinessHourEntity.businessHourId = savedBusinessHour.id;

                        promises.push(this.storeBusinessHourRepository.save(storeBusinessHourEntity, Shopware.Context.api));
                    });

                    promises.push(businessHourPromise);
                });
            }

            if (undefined !== this.businessHours['closedDays']) {
                this.businessHours['closedDays'].forEach(closedDay => {
                    const businessHourEntity       = this.businessHourRepository.create(Shopware.Context.api);
                    businessHourEntity.start       = closedDay.start;
                    businessHourEntity.end         = closedDay.end;
                    businessHourEntity.description = closedDay.description;

                    const businessHourPromise = this.businessHourRepository.save(businessHourEntity, Shopware.Context.api).then(result => {
                        const savedBusinessHour       = JSON.parse(result.config.data);
                        const storeBusinessHourEntity = this.storeBusinessHourRepository.create(Shopware.Context.api);
                        let specialDate               = closedDay.specialDate;

                        if (specialDate.includes('T', 0)) {
                            specialDate = closedDay.specialDate.substring(0, closedDay.specialDate.indexOf('T'));
                        }

                        storeBusinessHourEntity.storeId        = this.store.id;
                        storeBusinessHourEntity.active         = false;
                        storeBusinessHourEntity.annual         = closedDay.annual;
                        storeBusinessHourEntity.specialDate    = specialDate;
                        storeBusinessHourEntity.businessHourId = savedBusinessHour.id;

                        promises.push(this.storeBusinessHourRepository.save(storeBusinessHourEntity, Shopware.Context.api));
                    });

                    promises.push(businessHourPromise);
                });
            }

            return Promise.all(promises);
        },

        getWeekDays() {
            this.weekDayRepository.search(this.weekDayCriteria, Shopware.Context.api).then(result => {
                this.weekDays = result;
            });
        },

        async loadStoreBusinessHours() {
            await this.storeBusinessHourRepository.search(this.storeBusinessHoursCriteria, Shopware.Context.api).then(result => {
                result.forEach(storeBusinessHour => {
                    if (null !== storeBusinessHour.businessWeekdayId) {
                        this.$set(this.businessHours, storeBusinessHour.businessWeekdayId, this.businessHours[storeBusinessHour.businessWeekdayId] || [])

                        this.businessHours[storeBusinessHour.businessWeekdayId].push({
                            start: storeBusinessHour.businessHour.start,
                            end: storeBusinessHour.businessHour.end,
                            storeBusinessHourId: storeBusinessHour.id,
                            businessHourId: storeBusinessHour.businessHourId
                        });
                    } else {
                        if (storeBusinessHour.active) {
                            this.$set(this.businessHours, 'openDays', this.businessHours['openDays'] || []);

                            //the hours has to be adjusted, otherwise a wrong day will be displayed
                            const date = storeBusinessHour.specialDate + ' 12:00:00';

                            this.businessHours['openDays'].push({
                                specialDate: date,
                                start: storeBusinessHour.businessHour.start,
                                end: storeBusinessHour.businessHour.end,
                                description: storeBusinessHour.businessHour.translations.first().description,
                                annual: storeBusinessHour.annual,
                                businessHourId: storeBusinessHour.businessHourId
                            });

                        } else {
                            this.$set(this.businessHours, 'closedDays', this.businessHours['closedDays'] || []);

                            //the hours has to be adjusted, otherwise a wrong day will be displayed
                            const date = storeBusinessHour.specialDate + ' 12:00:00';

                            this.businessHours['closedDays'].push({
                                specialDate: date,
                                start: storeBusinessHour.businessHour.start,
                                end: storeBusinessHour.businessHour.end,
                                description: storeBusinessHour.businessHour.translations.first().description,
                                annual: storeBusinessHour.annual,
                                businessHourId: storeBusinessHour.businessHourId
                            });
                        }
                    }
                });
            });
        },

        validateBusinessHours() {
            let hasErrors = false;

            this.weekDays.forEach(weekDay => {
                if (hasErrors || undefined === this.businessHours[weekDay.id]) {
                    return;
                }

                this.businessHours[weekDay.id].every(businessHour => {
                    if ((businessHour.start === '' || businessHour.start === null)
                        || (businessHour.end === '' || businessHour.end === null)
                    ) {
                        hasErrors = true;

                        return false;
                    }

                    return true;
                });
            });

            if (hasErrors) {
                return hasErrors;
            }

            if (undefined !== this.businessHours['openDays']) {
                this.businessHours['openDays'].every(openDay => {
                    if ((openDay.start === '' || openDay.start === null)
                        || (openDay.end === '' || openDay.end === null)
                        || (openDay.specialDate === '' || openDay.specialDate === null)
                    ) {
                        hasErrors = true;

                        return false;
                    }

                    return true;
                });
            }

            if (hasErrors) {
                return hasErrors;
            }

            if (undefined !== this.businessHours['closedDays']) {
                this.businessHours['closedDays'].every(closedDay => {
                    if ((closedDay.start === '' || closedDay.start === null)
                        || (closedDay.end === '' || closedDay.end === null)
                        || (closedDay.specialDate === '' || closedDay.specialDate === null)
                    ) {
                        hasErrors = true;

                        return false;
                    }

                    return true;
                });
            }

            return hasErrors;
        },

        deleteOriginalHours() {
            let me         = this,
                httpClient = Shopware.Application.getContainer('init').httpClient,
                parameters = { 'storeId': this.storeId },
                headers    = {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${ Shopware.Context.api.authToken.access }`,
                'Content-Type': 'application/json'
            };

            if (me.isAborting) {
                return false;
            }

            return new Promise((resolve, reject) => {
                httpClient.post('_action/neti-store-locator/deleteBusinessHours', parameters, { headers })
                    .then(({ data }) => {
                        resolve(data);
                    })
                    .catch(reject);
            });
        },

        sortFields() {
            this.store.cmsPages.sort((a, b) => {
                return a.position - b.position;
            });
        },
    }
});
