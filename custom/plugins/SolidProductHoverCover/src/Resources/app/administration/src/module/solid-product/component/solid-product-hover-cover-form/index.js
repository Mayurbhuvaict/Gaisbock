/* global Shopware */

import template from './solid-product-hover-cover-form.html.twig';

const { Component, State, Context, Utils, Data } = Shopware;
const { mapGetters } = Component.getComponentHelper();
const { isEmpty } = Utils.types;
const { Criteria } = Data;

Component.register('solid-product-hover-cover-form', {
    template,

    inject: ['repositoryFactory', 'acl'],

    data() {
        return {
            mediaDefaultFolderId: null,
            showMediaModal: false,
            imageId: null,
        };
    },

    computed: {
        ...mapGetters('swProductDetail', ['isLoading']),

        mediaDefaultFolderRepository() {
            return this.repositoryFactory.create('media_default_folder');
        },

        mediaDefaultFolderCriteria() {
            const criteria = new Criteria(1, 1);

            criteria.addAssociation('folder');
            criteria.addFilter(Criteria.equals('entity', 'product'));

            return criteria;
        },

        product() {
            const state = State.get('swProductDetail');

            if (this.isInherited) {
                return state.parentProduct;
            }

            return state.product;
        },

        uploadTag() {
            return this.product.id + '-solid-hover-cover';
        },
    },

    watch: {
        imageId(value) {
            if (!this.product.customFields) {
                this.product.customFields = {
                    solidProductHoverCover: {
                        imageId: value
                    }
                };

                return;
            }

            this.product.customFields.solidProductHoverCover = {
                imageId: value
            };
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.imageId = this.getImageId();

            this.getMediaDefaultFolderId().then((mediaDefaultFolderId) => {
                this.mediaDefaultFolderId = mediaDefaultFolderId;
            });
        },

        getImageId() {
            if (
                this.product.customFields &&
                this.product.customFields.solidProductHoverCover
            ) {
                return this.product.customFields.solidProductHoverCover.imageId;
            }

            if (this.product.translated && this.product.translated.customFields && this.product.translated.customFields.solidProductHoverCover) {
                return this.product.translated.customFields.solidProductHoverCover.imageId;
            }

            return null;
        },

        getMediaDefaultFolderId() {
            return this.mediaDefaultFolderRepository
                .search(this.mediaDefaultFolderCriteria, Context.api)
                .then((mediaDefaultFolder) => {
                    const defaultFolder = mediaDefaultFolder.first();

                    if (defaultFolder.folder?.id) {
                        return defaultFolder.folder.id;
                    }

                    return null;
                });
        },

        onOpenMedia() {
            this.showMediaModal = true;
        },

        onAddMedia(media) {
            if (isEmpty(media)) {
                return;
            }

            this.addMedia(media[0].id);
        },

        onRemoveMedia() {
            this.imageId = null;
        },

        addMedia(mediaId) {
            this.imageId = mediaId;
        },

        onCloseMediaModal() {
            this.showMediaModal = false;
        },

        onUploadFinished({ targetId }) {
            this.addMedia(targetId);
        },
    },
});
