import template from "./template.twig";

const { Component } = Shopware;

Component.register("neti-store-locator-contact-form-detail-base", {
    template,

    data() {
        return {
            createMode: false
        };
    },

    props: {
        entity: {
            type: Object,
            required: true
        },
        editMode: {
            type: Boolean,
            required: true,
            default: false
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    }
});
