export default class GiftcardApiService extends Shopware.Classes.ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'lae-giftcard') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'giftcardApiService';
    }

    overview(additionalParams = {}, additionalHeaders = {}) {
        const route = `_action/lae-giftcard/overview`;

        const headers = {
            ...this.getBasicHeaders(additionalHeaders),
        };
        return this.httpClient
            .get(route, {additionalParams, headers});
    }
}
