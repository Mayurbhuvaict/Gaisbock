import template from "../detail-base/template.twig";

const { Component } = Shopware;

Component.extend("neti-store-locator-contact-form-create-base", "neti-store-locator-contact-form-detail-base", {
    template,

    data() {
        return {
            createMode: true
        };
    }
});
