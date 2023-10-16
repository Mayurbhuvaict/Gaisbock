export default class HttpClient {

    constructor(gateway) {
        this.gateway = gateway;
    }

    get(url) {
        return this.gateway.get(url);
    }

    post(url, data) {
        return this.gateway.post(url, data);
    }

}