import Backdrop, { BACKDROP_EVENT } from 'src/utility/backdrop/backdrop.util';

export default {
    install (app, options) {
        app.directive('offcanvas', {
            inserted(el, binding) {
                if (!binding.value) {
                    return;
                }

                el.classList.add('d-none');
            },

            componentUpdated(el, binding, vnode) {
                if (binding.value) {
                    if (binding.value.open) {
                        el.classList.add('offcanvas', 'offcanvas-end');
                        el.classList.remove('d-none');

                        if (el.classList.contains('is-open')) {
                            return;
                        }

                        setTimeout(() => {
                            el.classList.add('show');

                            Backdrop.create(() => {
                                if (typeof vnode.data.on.opened === 'function') {
                                    vnode.data.on.opened();
                                }
                            });
                        }, 75);

                        const onBackdropClick = () => {
                            if (typeof vnode.data.on.close === 'function') {
                                vnode.data.on.close();
                            }

                            // remove the event listener immediately to avoid multiple listeners
                            document.removeEventListener(BACKDROP_EVENT.ON_CLICK, onBackdropClick);
                        };

                        document.addEventListener(BACKDROP_EVENT.ON_CLICK, onBackdropClick);
                    } else {
                        el.classList.remove('show');

                        Backdrop.remove();
                        setTimeout(() => {
                            el.classList.remove('offcanvas', 'offcanvas-end');
                            el.classList.add('d-none');

                            if (typeof vnode.data.on.closed === 'function') {
                                vnode.data.on.closed();
                            }
                        }, 300);
                    }
                } else {
                    el.classList.remove('offcanvas', 'is-right', 'is-open');
                    Backdrop.remove();

                    if (typeof vnode.data.on.closed === 'function') {
                        vnode.data.on.closed();
                    }
                }
            }

        });
    }
}
