export default class ListingHelper
{
    /**
     * @returns { Object[] }
     */
    static getContents(listing) {
        const contents = [];

        if (typeof listing !== 'object') {
            return contents;
        }

        if (! Object.prototype.hasOwnProperty.call(listing, 'products')){
            return contents;
        }

        listing.products.forEach(function(item){
            contents.push({
                id: item.productNumber,
                quantity: 1
            });
        });

        return contents;
    }
}
