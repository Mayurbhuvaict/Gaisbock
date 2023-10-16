/* global Shopware */

import template from './solid-code-editor.html.twig';
import './solid-code-editor.scss';

const { Component } = Shopware;

Component.extend('solid-code-editor', 'sw-code-editor', {
    template,
    props: {
        helpText: {
            type: String,
            required: false,
        },
    },
});
