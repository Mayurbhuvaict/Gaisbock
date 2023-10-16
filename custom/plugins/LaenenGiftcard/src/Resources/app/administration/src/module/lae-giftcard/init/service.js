import GiftcardApiService from "../service/giftcard.api.service";

Shopware.Service().register('giftcardApiService', () => {
    return new GiftcardApiService(
        Shopware.Application.getContainer('init').httpClient,
        Shopware.Service('loginService'),
    );
});
