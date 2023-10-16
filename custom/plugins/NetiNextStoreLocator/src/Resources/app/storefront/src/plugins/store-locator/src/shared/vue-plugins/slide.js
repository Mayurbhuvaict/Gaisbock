function slideDown(el) {
    // Custom temp styles to determine height of element
    el.style.maxHeight  = 'auto';
    el.style.visibility = 'hidden';
    el.style.display    = 'block';
    el.style.overflow   = 'visible';

    let height = el.scrollHeight;

    // This happens usually when the element is hidden.
    if (height <= 0) {
        height = 'unset';
    }

    // Reset styles
    el.style.visibility = 'visible';

    // Custom required styles
    el.style.maxHeight  = '1px';
    el.style.overflow   = 'hidden';
    el.style.transition = 'max-height 0.3s linear';

    el.style.maxHeight = typeof height === 'string'
        ? height
        : height + 'px';

    resetOverflowLater(el);
}

function slideUp(el) {
    el.style.maxHeight  = '0px';
    el.style.overflow   = 'hidden';
    el.style.transition = 'max-height 0.3s linear';
}

function toggleSlide(el, slide) {
    if (slide) {
        slideDown(el);
    } else {
        slideUp(el);
    }
}

function resetOverflowLater(el) {
    const listener = () => {
        el.style.overflow = 'visible';

        el.removeEventListener('transitionend', listener);
    };

    el.addEventListener('transitionend', listener);
}

export const SlideDirective = {
    inserted(el, binding) {
        toggleSlide(el, binding.value);
    },
    componentUpdated(el, binding) {
        toggleSlide(el, binding.value);
    }
};

export default {
    install(app) {
        /**
         * Example:
         * <div v-slide="visible">
         *     Your dynamic content
         * </div>
         */
        app.directive('slide', SlideDirective);
    }
};