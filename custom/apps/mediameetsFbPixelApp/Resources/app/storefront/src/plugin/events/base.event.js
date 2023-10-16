import ApiService from '../services/api.service';

export default class BaseEvent
{
    /**
     *
     * @param plugin
     */
    constructor(plugin) {
        this.apiService = new ApiService(plugin.options.routes);
        this.shop = plugin.options.shop;
        this.config = plugin.options.config;
    }
}
