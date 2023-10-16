import SolidIeService from '../service/solid-ie.service';

const { Service, Application } = Shopware;

Service().register('solidIeService', () => {
    const initContainer = Application.getContainer('init');
    const serviceContainer = Application.getContainer('service');

    return new SolidIeService(
        initContainer.httpClient,
        serviceContainer.loginService,
    );
});
