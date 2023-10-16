import template from "./template.twig";

const { Component, Mixin } = Shopware;
const { Criteria }         = Shopware.Data;

Component.register("neti-store-locator-contact-form-list", {
    template,

    inject: [
        "repositoryFactory"
    ],

    mixins: [
        Mixin.getByName("notification"),
        Mixin.getByName("listing")
    ],

    data() {
        return {
            data: null,
            sortBy: "position",
            sortDirection: "ASC",
            naturalSorting: true,
            isLoading: false,
            showDeleteModal: false
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create("neti_store_locator_contact_form");
        },

        columns() {
            return this.getColumns();
        },

        defaultCriteria() {
            const criteria      = new Criteria(this.page, this.limit);
            this.naturalSorting = this.sortBy === "position";

            criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.naturalSorting));

            return criteria;
        }
    },

    methods: {
        getList() {
            this.isLoading = true;

            this.repository.search(this.defaultCriteria, Shopware.Context.api).then((items) => {
                this.total     = items.total;
                this.data      = items;
                this.isLoading = false;

                return items;
            }).catch(() => {
                this.isLoading = false;
            });
        },

        onDelete(id) {
            this.showDeleteModal = id;
        },

        onCloseDeleteModal() {
            this.showDeleteModal = false;
        },

        onConfirmDelete(id) {
            this.showDeleteModal = false;

            return this.repository.delete(id, Shopware.Context.api).then(() => {
                this.getList();
            });
        },

        onChangeLanguage(languageId) {
            this.getList(languageId);
        },

        getColumns() {
            return [
                {
                    property: "active",
                    dataIndex: "active",
                    label: this.$t("neti-store-locator-contact-form.list.column.active"),
                    width: "100px"
                },
                {
                    property: "label",
                    dataIndex: "label",
                    label: this.$t("neti-store-locator-contact-form.list.column.label"),
                    width: "300px",
                    allowResize: true,
                    primary: true
                },
                {
                    property: "type",
                    dataIndex: "type",
                    label: this.$t("neti-store-locator-contact-form.list.column.type"),
                    width: "150px"
                },
                {
                    property: "required",
                    dataIndex: "required",
                    label: this.$t("neti-store-locator-contact-form.list.column.required"),
                    width: "100px"
                },
                {
                    property: "position",
                    dataIndex: "position",
                    label: this.$t("neti-store-locator-contact-form.list.column.position"),
                    width: "100px"
                }
            ];
        }
    }
});
