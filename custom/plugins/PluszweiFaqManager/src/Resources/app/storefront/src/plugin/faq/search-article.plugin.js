import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';
import DomAccess from 'src/helper/dom-access.helper';
import Debouncer from 'src/helper/debouncer.helper';
import ButtonLoadingIndicator from 'src/utility/loading-indicator/button-loading-indicator.util';
import Iterator from 'src/helper/iterator.helper';
import DeviceDetection from 'src/helper/device-detection.helper';

export default class SearchArticlePlugin extends Plugin {
    static options = {
        searchResultSelector: '.js-search-result',
        searchDelay: 250,
        searchMinChars: 3,
    };

    init() {
        try {
            this._inputField = DomAccess.querySelector(this.el, 'input[type=search]');
            this._submitButton = DomAccess.querySelector(this.el, 'button[type=submit]');
            this._url = DomAccess.getAttribute(this.el, 'data-url');
        } catch (e) {
            return;
        }

        this._client = new HttpClient();

        this._registerEvents();
    }

    _registerEvents() {
        this._inputField.addEventListener(
            'input',
            Debouncer.debounce(this._handleInputEvent.bind(this), this.options.searchWidgetDelay),
            {
                capture: true,
                passive: true,
            },
        );

        this.el.addEventListener('submit', this._handleSearchEvent.bind(this));

        const event = (DeviceDetection.isTouchDevice()) ? 'touchstart' : 'click';
        document.body.addEventListener(event, this._onBodyClick.bind(this));
    }

    _handleSearchEvent(event) {
        const value = this._inputField.value.trim();

        // stop search if minimum input value length has not been reached
        if (value.length < this.options.searchMinChars) {
            event.preventDefault();
            event.stopPropagation();
        }
    }

    _handleInputEvent() {
        const value = this._inputField.value.trim();

        // stop search if minimum input value length has not been reached
        if (value.length < this.options.searchMinChars) {
            // further clear possibly existing search results
            this._clearSuggestResults();
            return;
        }

        this._suggest(value);
    }

    _suggest(value) {
        const url = this._url + encodeURIComponent(value);

        const indicator = new ButtonLoadingIndicator(this._submitButton);
        indicator.create();

        this._client.abort();
        this._client.get(url, (response) => {
            this._clearSuggestResults();
            indicator.remove();
            this.el.insertAdjacentHTML('beforeend', response);
        });
    }

    _clearSuggestResults() {
        // remove all result popovers
        const results = document.querySelectorAll(this.options.searchResultSelector);
        Iterator.iterate(results, result => result.remove());
    }

    _onBodyClick(e) {
        if (e.target.closest(this.options.searchResultSelector)) {
            return;
        }
        this._clearSuggestResults();
    }
}