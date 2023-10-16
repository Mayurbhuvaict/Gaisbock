export default class CartAndOrderHelper
{
    /**
     * @returns { Object[] }
     */
    static getContents(cartOrOrder) {

        const contents = [];

        cartOrOrder.lineItems.forEach((item) => {
            if (item.type !== 'product') {
                return;
            }

            contents.push({
                id: item.payload.productNumber,
                quantity: item.quantity
            });
        });

        return contents;
    }

    /**
     * @returns {number}
     */
    static getTotalItems(cartOrOrder) {

        let numItems = 0;
        cartOrOrder.lineItems.forEach((item) => {
            if (item.type !== 'product') {
                return;
            }

            numItems += item.quantity;
        });

        return numItems;
    }

    /**
     * @returns { Object[] }
     */
    static getProductByNumber(cartOrOrder, productNumber) {

        let product = null;
        cartOrOrder.lineItems.forEach((item) => {
            if (item.type === 'product' && item.payload.productNumber === productNumber) {
                product = item;
            }
        });

        return product;
    }

    /**
     * @returns { Object[] }
     */
    static getProductById(cartOrOrder, id) {

        let product = null;
        cartOrOrder.lineItems.forEach((item) => {
            if (item.type === 'product' && item.id === id) {
                product = item;
            }
        });

        return product;
    }
}
