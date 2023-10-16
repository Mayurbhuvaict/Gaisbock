import template from './template.twig';
import errorConfig from './error-config.json';

const { Component, Mixin } = Shopware;
const { Criteria }         = Shopware.Data;
const { mapPageErrors }    = Shopware.Component.getComponentHelper();

Component.register('neti-store-locator-contact-form-detail', {
    template,

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder'),
        // Mixin.getByName("discard-detail-page-changes")("neti_store_locator_contact_form")
    ],

    shortcuts: {
        'SYSTEMKEY+S': 'onSave',
        ESCAPE: 'onAbortButtonClick'
    },

    beforeRouteLeave(to, from, next) {
        this.editMode = false;
        next();
    },

    data() {
        return {
            isLoading: false,
            entity: null,
            entityId: null,
            editMode: false,
            isSaveSuccessful: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.identifier)
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('neti_store_locator_contact_form');
        },
        isCreateEntity() {
            return this.$route.name.includes('neti.store_locator.contact_form.create');
        },
        createMode() {
            return this.$route.name.includes('create');
        },
        defaultCriteria() {
            const criteria = new Criteria();

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
            if (this.$route.params.id) {
                this.entityId = this.$route.params.id;

                if (!this.createMode) {
                    this.repository.get(
                        this.entityId,
                        Shopware.Context.api,
                        this.defaultCriteria
                    ).then((entity) => {
                        this.entity = entity;
                        this.initializeFurtherComponents();
                    });
                } else {
                    if (!Shopware.State.getters['context/isSystemDefaultLanguage']) {
                        Shopware.State.commit('context/resetLanguageToDefault');
                    }

                    this.initializeFurtherComponents();
                }
            }

            if (this.$route.params.edit === 'edit') {
                this.editMode = true;
            }
        },

        abortOnLanguageChange() {
            return this.repository.hasChanges(this.entity);
        },

        saveOnLanguageChange() {
            return this.onSave();
        },

        onChangeLanguage() {
            this.createdComponent();
        },

        initializeFurtherComponents() {
            if (!this.entity) {
                return;
            }

            this.isLoading = false;
        },

        saveFinish() {
            this.isSaveSuccessful = false;
            this.editMode         = false;
        },

        onActivateEditMode() {
            this.editMode = true;
        },

        onSave() {
            if (!this.editMode) {
                return false;
            }

            this.isSaveSuccessful = false;
            this.isLoading        = true;

            return this.repository.save(this.entity, Shopware.Context.api).then(() => {
                this.isLoading        = false;
                this.isSaveSuccessful = true;
                this.createdComponent();
            }).catch((exception) => {
                /*this.createNotificationError({
                 title: this.$t("neti-store-locator.detail.titleSaveError"),
                 message: this.$t("neti-store-locator.detail.messageSaveError")
                 });*/

                this.isLoading = false;
            });
        },

        onAbortButtonClick() {
            // todo: Write a custom method since discarding changes on tab switch kills
            // neti.store_locator.contact_form.create this.discardChanges();
            if (this.createMode === true) {
                this.$router.push({ name: 'neti.store_locator.contact_form.overview' });
                this.isLoading = false;
            }
            this.editMode = false;
        }
    }
});
