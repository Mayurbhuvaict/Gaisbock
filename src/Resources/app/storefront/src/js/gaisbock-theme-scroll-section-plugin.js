import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockThemeScrollSection extends Plugin {
    init() {
        this._gaisbockThemeScroll();
        const overlay = document.querySelector('.cms-block-gaisbock-custom-text-on-image');
        const content = document.querySelector('.static-banner-section');

        window.addEventListener('scroll', () => {
        // Calculate scroll progress
        const scrollProgress = (window.scrollY / content.clientHeight);

        // Calculate opacity based on scroll progress
        const overlayOpacity = Math.min(0.7, scrollProgress * 0.7);

        // Update overlay background color
        overlay.style.backgroundColor = `rgba(0, 0, 0, ${overlayOpacity})`;
        });
    }

    _gaisbockThemeScroll() {
        var anchorLinks = document.querySelector('#gaisbock-down-arrow');
        anchorLinks.addEventListener('click', function (e) {
            e.preventDefault();

            var targetPosition = 500; // Adjust this value for the target scroll position
            var scrollSpeed = 10; // Adjust this value for the scrolling speed
            var currentPosition = window.pageYOffset;
            var scrolling = true;

            function scrollStep(timestamp) {
                if (!scrolling) return;

                var progress = (timestamp - startTime) / scrollDuration;
                var newPosition = currentPosition + (targetPosition - currentPosition) * progress;

                if (progress < 1) {
                    window.scrollTo(0, newPosition);
                    requestAnimationFrame(scrollStep);
                } else {
                    window.scrollTo(0, targetPosition);
                    scrolling = false;
                }
            }

            var scrollDuration = Math.abs(targetPosition - currentPosition) / scrollSpeed;
            var startTime = performance.now();

            if (scrollDuration > 0) {
                scrolling = true;
                requestAnimationFrame(scrollStep);
            }
        });
    }

}