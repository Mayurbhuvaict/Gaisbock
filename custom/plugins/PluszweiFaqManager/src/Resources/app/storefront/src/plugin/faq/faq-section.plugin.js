import Plugin from 'src/plugin-system/plugin.class';
import DomAccess from 'src/helper/dom-access.helper';
import Iterator from 'src/helper/iterator.helper';

export default class FaqSectionPlugin extends Plugin {
    static options = {
        hideSelector: '.hide',
        seeMoreSelector: '.seeMore',
    };

    init() {
        try {
            this._hideSelectors = DomAccess.querySelectorAll(this.el, this.options.hideSelector);
            this._seeMoreSelector = DomAccess.querySelector(this.el, this.options.seeMoreSelector);
        } catch (e) {
            return;
        }

        this._registerEvents();
    }

    _registerEvents() {
        this._seeMoreSelector.addEventListener('click', this._onClickSeeMore.bind(this))
    }

    _onClickSeeMore(event) {
        this._seeMoreSelector.remove();
        Iterator.iterate(this._hideSelectors, result => result.classList.remove('hide'));
    }
}