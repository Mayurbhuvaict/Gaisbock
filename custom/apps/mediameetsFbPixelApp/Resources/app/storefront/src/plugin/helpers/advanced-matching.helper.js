export default class AdvancedMatchingHelper
{
    /**
     *
     * @param {object|null} customer
     * @returns {{ct: (string|null), ln: (string|null), bd: (string|null), zp: (string|null), em: (string|null), fn: (string|null), ph: (string|null), cn: (string|null), ge: (string|null)}|null}
     */
    static getData(customer) {
        const me = this;

        if (customer === null) {
            return null;
        }

        return {
            em: me._lowerCase(customer.email),
            fn: me._lowerCase(customer.firstName),
            ln: me._lowerCase(customer.lastName),
            ge: me._gender(customer.salutation.salutationKey),
            bd: me._birthday(customer.birthday),
            ph: me._lowerCase(customer.activeShippingAddress.phoneNumber),
            ct: me._lowerCase(customer.activeShippingAddress.city),
            zp: me._lowerCase(customer.activeShippingAddress.zipcode),
            cn: me._lowerCase(customer.activeShippingAddress.country.iso)
        };
    }

    /**
     *
     * @param val
     * @returns {string|null}
     * @private
     */
    static _lowerCase(val) {
        if (typeof val === 'string') {
            return val.toLowerCase();
        }

        return null;
    }

    /**
     *
     * @param {string|null} gender
     * @returns {string|null}
     * @private
     */
    static _gender(gender) {
        if (gender === null) {
            return null;
        }

        const genders = {
            'mr': 'm',
            'mrs': 'f'
        };
        return genders[gender] ? genders[gender] : null;
    }

    /**
     *
     * @param {string|null} val
     * @returns {string|null}
     * @private
     */
    static _birthday(val) {
        if (val === null) {
            return null;
        }

        const dateObj = new Date(val);
        // eslint-disable-next-line
        if (isNaN(dateObj)) {
            return null;
        }

        function pad(n){return n<10 ? '0'+n : n}

        return [
            dateObj.getFullYear(),
            pad(dateObj.getMonth() + 1),
            pad(dateObj.getDate())
        ].join('');
    }
}
