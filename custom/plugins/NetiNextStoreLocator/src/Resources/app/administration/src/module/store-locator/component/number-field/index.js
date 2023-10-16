Shopware.Component.extend('neti-store-locator-number-field', 'sw-number-field', {

    methods: {
        getNumberFromString(value) {
            let splits = value.split('e').shift();
            splits     = splits.replace(/,/g, '.').split('.');

            if (splits.length === 1) {
                return parseFloat(splits[0]);
            }

            if (this.numberType === 'int') {
                return parseInt(splits.join(''), 10);
            }

            const { decimals, transfer } = this.applyDigits(splits[splits.length - 1]);
            const integer                = parseInt(splits.slice(0, splits.length - 1).join(''), 10) + transfer;

            if (0 === integer && '-' === value[0]) {
                return parseFloat(`-${ integer }.${ decimals }`);
            }

            return parseFloat(`${ integer }.${ decimals }`);
        },

        applyDigits(decimals) {
            if (decimals.length <= this.digits) {
                return {
                    decimals,
                    transfer: 0,
                };
            }

            let asString = decimals.substr(0, this.digits + 1);
            let asNumber = parseFloat(asString);
            asNumber = Math.round(asNumber / 10);
            asString = asNumber.toString();

            if (asString.length > this.digits) {
                return {
                    decimals: asString.substr(1, asString.length),
                    transfer: 1,
                };
            }

            asString = '0'.repeat(this.digits - asString.length) + asString;
            return {
                decimals: asString,
                transfer: 0,
            };
        },
    }

});