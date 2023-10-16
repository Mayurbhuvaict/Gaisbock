import ApiService from '../services/api.service';

export default class BaseTrigger
{
    active = true;

    /**
     *
     * @param plugin
     */
    constructor(plugin) {
        this.plugin = plugin;
        this.apiService = new ApiService(plugin.options.routes);
    }

    /* eslint-disable no-unused-vars */
    /**
     * @param {string} controllerName
     * @param {string} actionName
     * @returns {boolean}
     */
    supports(controllerName, actionName) {
        this.warn('Method \'supports\' was not overridden by `' + this.constructor.name + '`. Default return set to false.');
        return false;
    }
    /* eslint-enable no-unused-vars */

    execute() {
        this.warn('Method \'execute\' was not overridden by `' + this.constructor.name + '`.');
    }

    disable() {
        this.active = false;
    }

    initEvent(event) {
        return new event(this.plugin);
    }

    /**
     * @param {string} message
     */
    warn(message) {
        console.warn('[Facebook Pixel Plugin (media:meets GmbH)] ' + message);
    }

    /**
     * @param {string} key
     * @returns {string}
     */
    getStorageKey(key) {
        return 'mediameetsFacebookPixel.' + key;
    }
}
