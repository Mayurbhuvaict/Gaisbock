import template from './template.twig';
import './style.scss';

const { Component } = Shopware;
const ApiService = Shopware.Classes.ApiService;

Component.register('pluszwei-faq-manager-welcome', {
    inject: [],
    template,

    data() {
        return {
            isLoading: true,
            content: null,
            httpClient: null
        }
    },

    created() {
        const syncService = Shopware.Service('syncService');
        this.httpClient = syncService.httpClient;

        this.createdComponent();
    },

    computed: {

    },

    methods: {
        async createdComponent() {
            let url = 'https://www.pluszwei.io/api/'
            let site = 'pluszwei';
            if (Shopware.State.get('session').currentLocale !== 'de-DE') {
                site = 'pluszweiEn';
            }

            this.httpClient.post(
                url,
                `query Entries {
                  entries(section: "widget", slug: "shopware-6-app-config-header", site:"${site}") {
                    id
                    slug
                    ... on widget_default_Entry {
                      title
                      description
                    }
                  }
                }`,
                {
                    headers: {
                        'Content-Type': 'application/graphql',
                        'Authorization': 'Bearer uKQTLgSV9qY-JdhD6CgR0Po1p0iKszBn',
                    }
                }
            ).then((res) => {
                const result = ApiService.handleResponse(res)
                if (result.data && result.data.entries) {
                    this.content = result.data.entries[0].description
                }
                this.isLoading = false;
            })
        }
    }
})
