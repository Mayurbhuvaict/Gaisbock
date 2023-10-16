import template from './template.twig';

const { Criteria } = Shopware.Data;

Shopware.Component.register('neti-store-locator-details-content-card', {
    template,

    props: {
        store: {
            type: Object,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    computed: {
        cmsPageCriteria() {
            let criteria = new Criteria();

            return criteria.addFilter(Criteria.equals('type', 'neti_store_locator'));
        }
    },

    methods: {
        onDetailsPictureMediaSelected(media) {
            this.store.detailsPictureMedia   = media;
            this.store.detailsPictureMediaId = media ? media.id : null;
        },

        onSelectCmsPage(cmsPageId, cmsPage) {
            if (cmsPage) {
                this.store.cmsPageVersionId = cmsPage.versionId;
            } else {
                this.store.cmsPageVersionid = null;
            }
        }
    }
});
