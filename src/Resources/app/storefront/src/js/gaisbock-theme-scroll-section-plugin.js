import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockThemeScrollSection extends Plugin {
    init() {
        this._gaisbockThemeScroll();
    }

    _gaisbockThemeScroll() {
        const anchorLinks = document.getElementById('gaisbock-down-arrow');

        anchorLinks.addEventListener('click', function (event) {
            event.preventDefault();
            const targetTop = anchorLinks.getBoundingClientRect().top;
            const startingY = window.pageYOffset;
            const duration = 500;
            let startTime = null;

            function scrollAnimation(currentTime) {
                if (!startTime) startTime = currentTime;
                const timeElapsed = currentTime - startTime;
                const progress = Math.min(timeElapsed / duration, 1);
                const easeFunction = function (t) {
                    return t * (2 - t);
                };
                const easedProgress = easeFunction(progress);
                const distance = targetTop - startingY;
                window.scrollTo(0, startingY + distance * easedProgress);

                if (timeElapsed < duration) {
                    requestAnimationFrame(scrollAnimation);
                }
            }
            requestAnimationFrame(scrollAnimation);
        });
    }

}