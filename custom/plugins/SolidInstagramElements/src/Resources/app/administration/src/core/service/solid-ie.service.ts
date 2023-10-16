import ApiService from '@administration/core/service/api.service';

/**
 * Gateway for the API end point "solid-ie"
 * @class
 * @extends ApiService
 */
class SolidIeService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'solid-ie') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'solidIeService';
    }

    fetchAndStoreLatestPosts() {
        const headers = this.getBasicHeaders();
        const payload = {};

        return this.httpClient
            .post(`/_action/${this.apiEndpoint}/fetch-and-store-latest-posts`, payload, {
                headers,
            })
            .then((response) => {
                return ApiService.handleResponse(response);
            });
    }
}

export default SolidIeService;
