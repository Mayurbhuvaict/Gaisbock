import template from "./template.twig";

const { Component }    = Shopware;
const { mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register("neti-store-locator-contact-form-general-basic-card", {
    template,

    props: {
        entity: {
            type: Object,
            required: true
        },
        editMode: {
            type: Boolean,
            required: false,
            default: false
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    computed: {
        availableTypes() {
            return [
                { key: "textfield" },
                { key: "textarea" },
                { key: "checkbox" },
                { key: "radio" },
                { key: "select" },
                { key: "subject" },
                { key: "email" },
                { key: "email_copy" },
                { key: "file_upload" }
            ];
        },
        ...mapPropertyErrors("entity", ["label", "type"])
    }
});
