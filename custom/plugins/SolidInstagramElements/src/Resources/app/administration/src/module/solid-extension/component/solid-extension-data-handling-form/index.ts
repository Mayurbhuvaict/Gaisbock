import template from './solid-extension-data-handling-form.html.twig';

const { Component } = Shopware;

/**
 * @private
 */
Component.register('solid-extension-data-handling-form', {
    template,

    inject: ['solidIeService'],

    props: {
        value: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            storedValue: this.value,
            fetchAndStoreLatestPostsDisabled: !this.value,
        }
    },

    methods: {
        onChange(value) {
            this.$emit('input', value);

            if (!value) {
                this.fetchAndStoreLatestPostsDisabled = true;
                return;
            }

            if (this.storedValue) {
                this.fetchAndStoreLatestPostsDisabled = false;
            }
        },

        onClickFetchAndStoreLatestPosts() {
            this.fetchAndStoreLatestPostsDisabled = true;
            this.solidIeService.fetchAndStoreLatestPosts();
        }
    }
});
