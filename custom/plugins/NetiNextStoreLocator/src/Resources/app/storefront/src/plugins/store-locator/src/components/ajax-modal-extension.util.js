import HttpClient from 'src/service/http-client.service';
import DomAccess from 'src/helper/dom-access.helper';
import PseudoModalUtil from 'src/utility/modal-extension/pseudo-modal.util';
import Iterator from 'src/helper/iterator.helper';

const URL_DATA_ATTRIBUTE = 'data-neti-url';

export default class AjaxModalExtensionUtil {
    constructor(element, updateFn) {
        this._element  = element;
        this._updateFn = updateFn;
        this._client   = new HttpClient();
        this._subscribed = false;
    }

    subscribe() {
        if (true === this._subscribed) {
            return;
        }

        const modalTriggers = this._element.querySelectorAll(`[data-ajax-modal="true"][${ URL_DATA_ATTRIBUTE }]`);
        if (modalTriggers) {
            Iterator.iterate(
                modalTriggers,
                trigger => trigger.addEventListener('click', this._onClickHandleAjaxModal.bind(this))
            );
        }

        this._subscribed = true;
    }

    _onClickHandleAjaxModal(event) {
        event.preventDefault();
        event.stopPropagation();

        const trigger = event.currentTarget;
        const url     = DomAccess.getAttribute(trigger, URL_DATA_ATTRIBUTE);

        this._currentModalClass = trigger.getAttribute('data-modal-class');

        this._client.get(url, response => this._openModal(response));
    }

    _openModal(response) {
        const pseudoModal = new PseudoModalUtil(response, true);

        pseudoModal.open(() => {
            window.PluginManager.initializePlugins();
        });

        const modal = pseudoModal.getModal();

        if (this._currentModalClass) {
            modal.classList.add(this._currentModalClass);
        }

        this._updateFn(true);

        modal.addEventListener('hidden.bs.modal', () => {
            this._updateFn(false);
        });
    }
}
