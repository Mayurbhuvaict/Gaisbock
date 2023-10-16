export default {
    install (app, options) {
        app.directive('scroll', {
            inserted(el, binding, vnode) {
                let isTouching  = false;
                let timeout     = null;
                let hasScrolled = false;

                el.addEventListener('touchstart', () => {
                    isTouching = true;
                });

                el.addEventListener('scroll', () => {
                    hasScrolled = true;

                    if (isTouching) {
                        return;
                    }

                    if (timeout) {
                        clearTimeout(timeout);
                        timeout = null;
                    }

                    timeout = setTimeout(() => {
                        if (el._scrolling) {
                            return;
                        }

                        vnode.data.on['scroll-end']();
                    }, 50);
                });

                el.addEventListener('touchend', () => {
                    if (hasScrolled) {
                        if (el._scrolling) {
                            return;
                        }

                        vnode.data.on['scroll-end']();
                        clearTimeout(timeout);
                    }

                    isTouching  = false;
                    hasScrolled = false;
                });
            }
        });
    }
}