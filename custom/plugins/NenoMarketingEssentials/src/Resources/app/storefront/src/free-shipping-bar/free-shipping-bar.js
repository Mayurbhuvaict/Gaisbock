import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';
import Storage from 'src/helper/storage/storage.helper';

export default class FreeShippingBar extends Plugin {
    static options = {
        shippingBarWidgetStorageKey: 'nenoMarketingEssentialsFreeShippingWidget',
        hideBarWhenNoItems: false,
    };

    init() {
        this._client = new HttpClient();

        this._updateMessage = this._updateMessage.bind(this);
        this._fetchRemainingAmount = this._fetchRemainingAmount.bind(this);

        this.options.hideBarWhenNoItems = !!this.el.getAttribute('data-hide-bar-when-no-items');

        this._messageContainerEl = this.el.querySelector('.nme-free-shipping-bar__message');

        this._subscribeToCartUpdate();
        this._applyStoredContent();

        if (this.options.hideBarWhenNoItems) {
            this._hideBar();
        }

        this._fetchRemainingAmount();
    }

    _showBar() {
        this.el.style.maxHeight = '';
    }

    _hideBar() {
        this.el.style.maxHeight = 0;
    }

    /**
     * reads the persisted content
     * from the session cache an renders it
     * into the element
     */
    _applyStoredContent() {
        const storedContent = Storage.getItem(this.options.shippingBarWidgetStorageKey)

        if (storedContent) {
            this._updateMessage(storedContent);
        }
    }

    _subscribeToCartUpdate() {
        const CartOffCanvasPluginInstances = PluginManager.getPluginInstances('OffCanvasCart');

        const OffCanvasCartInstance = CartOffCanvasPluginInstances && CartOffCanvasPluginInstances[0];

        if (!OffCanvasCartInstance) {
            // NOTE: On some pages the off canvas cart may not be available.
            return;
        }

        OffCanvasCartInstance.$emitter.subscribe('fetchCartWidgets', () => {
            this._fetchRemainingAmount();
        });
    }

    _fetchRemainingAmount() {
        const baseUrl = this.el.getAttribute('data-base-url');

        this._client.get(`${baseUrl}widgets/neno-marekting-essentials/free-shipping-bar/info`, (response) => {
            Storage.setItem(this.options.shippingBarWidgetStorageKey, response);
            this._updateMessage(response);
        });
    }

    _updateMessage(response) {
        const freeShippingGoal = this.el.getAttribute('data-free-shipping-goal');

        if (freeShippingGoal && (typeof response === 'string')) {
            this._messageContainerEl.innerHTML = response;

            if (this._messageContainerEl.children.length) {
                this._showBar();
            } else {
                this._hideBar();
            }
        }
    }
}
