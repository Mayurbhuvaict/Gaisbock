import HttpClient from 'src/service/http-client.service';

export default class ApiService {

    constructor(routes) {
        this._client = new HttpClient();
        this.routes = routes;
    }

    loadContext(callback) {
        this._client.post(this.routes['context'], null, (response) => {
            callback(JSON.parse(response));
        });
    }

    loadCart(callback) {
        this._client.get(this.routes['cart'], (response) => {
            callback(JSON.parse(response));
        });
    }

    loadOrder(orderId, callback) {
        this._client.post(this.routes['order'], JSON.stringify({ orderId: orderId }), (response) => {
            callback(JSON.parse(response));
        });
    }
}
