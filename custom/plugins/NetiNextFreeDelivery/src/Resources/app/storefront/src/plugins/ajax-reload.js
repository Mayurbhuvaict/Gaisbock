import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';

export default class AjaxReload extends Plugin {
    static options = {
        url: '',
    };

    init() {
        this.http = new HttpClient(window.accessKey, window.contextToken);

        this.registerEvents();
    }

    registerEvents() {
        // Refresh content when ajax cart is opened (or internally refreshed by any actions)
        window.PluginManager.getPluginInstances('OffCanvasCart').forEach(plugin => {
            plugin.$emitter.subscribe('offCanvasOpened', this.onReload.bind(this));
        });
    }

    onReload() {
        if (this._removedFromDom(this.el)) {
            return;
        }

        const xhr = this.http.get(this.options.url, () => {
            if (xhr.status === 200) {
                this.el.innerHTML = xhr.responseText;
            }
        });
    }

    _removedFromDom (el) {
        let parent = el.parentNode;

        while (parent.parentNode) {
            if (parent.parentNode.nodeName === '#document') {
                return false;
            }

            parent = parent.parentNode;
        }

        return true;
    }
}

window.PluginManager.register('NetiAjaxReload', AjaxReload, '.neti-ajax-reload');
