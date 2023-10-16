import template from "./sw-product-detail-base.html.twig";
import './sw-product-detail-base.scss';

Shopware.Component.override('sw-product-detail-base', {
    template,

    computed: {
        giftcardEnabled: {
            get () {
                return this.product.customFields.laeGiftcardEnabled;
            },
            set (newValue) {
                this.product.customFields.laeGiftcardEnabled = newValue;
            }
        },

        giftcardDesignType: {
            get () {
                return this.product.customFields.laeGiftcardDesignType;
            },
            set (newValue) {
                this.product.customFields.laeGiftcardDesignType = newValue;
            }
        },

        designTypeOptions() {
            return [
                {value: 'simple', label: this.$tc('lae-giftcard.designType.simple')}
            ];
        }
    }
});
