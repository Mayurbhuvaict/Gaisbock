import { on } from '../../shared/util/events';
import { serializeFormToFormData } from '../../shared/util';
import HttpGateway from '../http-gateway';
import HttpClient from '../../shared/http-client';
import AjaxModalExtensionUtil from '../ajax-modal-extension.util';

export default {
    template: '#neti-store-locator-contact-form',
    props: {
        store: {
            required: false,
            type: Object
        }
    },
    data() {
        return {
            isLoading: false,
            wasValidated: false,
            isSuccess: false,
            isError: false,
            isChildModalShown: false,

            fileUploadFields: [],
            invalidUploadFields: {},

            modal: null
        };
    },
    watch: {
        isChildModalShown(value) {
            if (value) {
                this.$el.classList.add('child-modal-shown');
            } else {
                this.$el.classList.remove('child-modal-shown');
            }
        }
    },
    mounted() {
        const me = this;

        me.httpClient = new HttpClient(new HttpGateway());

        on('netiStoreLocator.contact', me.onOpen.bind(me));

        this.$nextTick(() => {
            this.ajaxModalUtil = new AjaxModalExtensionUtil(me.$el, (shown) => {
                this.isChildModalShown = shown;
            });
        })
    },
    beforeDestroy() {
        off('netiStoreLocator.contact');
    },
    methods: {
        onOpen() {
            const me = this;

            if (null === this.modal) {
                this.modal = new bootstrap.Modal(this.$el);
            }

            me.$nextTick(() => {
                me.resetForm();
                me.initFileUploadFields();

                this.modal.show();
                this.ajaxModalUtil.subscribe();
            });
        },
        onSubmit() {
            const me    = this;
            const $form = me.$refs.form;

            me.wasValidated = true;

            if (me.hasInvalidUploadFields()) {
                me.isError = true;
                return;
            }

            if ($form.checkValidity()) {
                me.isLoading = true;
                me.resetAlert();

                me.httpClient.post(
                    $form.getAttribute('action'),
                    serializeFormToFormData($form)
                ).then(response => {
                    try {
                        response = JSON.parse(response);

                        if (!response.success) {
                            throw new Error(); // Kinda go to exception catching here
                        }

                        me.resetForm();
                        me.isSuccess = true;
                    } catch (error) {
                        me.isError = true;

                        throw error;
                    }
                }).catch(error => {
                    me.isError = true;
                }).finally(() => {
                    me.isLoading = false;
                })
            }
        },
        resetForm() {
            const me = this;

            me.$refs.form.reset();
            me.wasValidated = false;

            me.resetAlert();
        },
        resetAlert() {
            const me = this;

            me.isSuccess = false;
            me.isError   = false;
        },
        initFileUploadFields() {
            const me = this;

            if (me.fileUploadFields.length > 0) {
                return;
            }

            // Load expected fields into array for easier access
            for (let key in me.$refs) {
                if (me.$refs.hasOwnProperty(key) && key.indexOf('fileUpload_') === 0) {
                    me.fileUploadFields.push(me.$refs[key]);
                }
            }

            me.fileUploadFields.forEach($field => {
                const fieldId           = $field.getAttribute('data-field');
                const allowedTypesRaw   = ($field.getAttribute('data-allowed-types') || '').trim().toLowerCase();
                const allowedExtensions = allowedTypesRaw.split(',');

                $field.addEventListener('change', () => {
                    const file = $field.files[0] || null;

                    if (file) {
                        const extension = file.name.substr(file.name.lastIndexOf('.') + 1).toLowerCase();

                        if (allowedExtensions.indexOf(extension) === -1 || '' === allowedTypesRaw) {
                            me.$set(me.invalidUploadFields, fieldId, true);
                            $field.classList.add('invalid-file-type');
                        } else {
                            me.$set(me.invalidUploadFields, fieldId, undefined);
                            $field.classList.remove('invalid-file-type');
                        }
                    } else {
                        me.$set(me.invalidUploadFields, fieldId, undefined);
                        $field.classList.remove('invalid-file-type');
                    }
                });
            });
        },
        hasInvalidUploadFields() {
            for (let key in this.invalidUploadFields) {
                if (this.invalidUploadFields.hasOwnProperty(key) && true === this.invalidUploadFields[key]) {
                    return true;
                }
            }

            return false;
        }
    }
};
