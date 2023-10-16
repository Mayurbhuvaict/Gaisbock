import ShopwareHttpClient from 'src/service/http-client.service';

export default class HttpGateway {

    constructor(placeholder) {
        this.client = new ShopwareHttpClient(window.accessKey, window.contextToken);
    }

    async get(url) {
        return new Promise((resolve, reject) => {
            const request = this.client.get(url, response => {
                if (request.status === 200) {
                    resolve(
                        JSON.parse(response)
                    );
                } else {
                    reject({ request, response });
                }
            });
        });
    }

    async post(url, data) {
        return new Promise((resolve, reject) => {
            const request = this.client.post(
                url,
                data,
                response => {
                    if (request.status === 200) {
                        resolve(response);
                    } else {
                        reject({ request, response });
                    }
                }
            );
        });
    }

}