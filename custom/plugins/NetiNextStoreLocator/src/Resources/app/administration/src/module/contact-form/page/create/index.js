import template from "./template.twig";

const { Component } = Shopware;
const utils         = Shopware.Utils;

Component.extend("neti-store-locator-contact-form-create", "neti-store-locator-contact-form-detail", {
    template,

    beforeRouteEnter(to, from, next) {
        if (to.name.includes("neti.store_locator.contact_form.create") && !to.params.id) {
            to.params.id = utils.createId();
        }

        next();
    },

    methods: {
        createdComponent() {
            this.entity = this.repository.create(Shopware.Context.api, this.$route.params.id);

            this.$super("createdComponent");

            this.isLoading = false;
            this.editMode  = true;
        },

        saveFinish() {
            this.isSaveSuccessful = false;
            this.$router.push({ name: "neti.store_locator.contact_form.detail", params: { id: this.entity.id } });
        },

    }
});
