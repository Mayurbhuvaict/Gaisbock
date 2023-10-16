export default class DataService {

    constructor () {
        this.data = window.mediameetsFacebookPixelData;
    }

    /**
     *
     * @param {string} key
     * @returns {null|object}
     */
    get(key) {
        if (! this.data.has(key)) {
            return null;
        }

        return this.data.get(key);
    }
}
