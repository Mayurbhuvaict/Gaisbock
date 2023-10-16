import Plugin from 'src/plugin-system/plugin.class';
import { tns } from 'tiny-slider/src/tiny-slider.module';

export default class ConversionBar extends Plugin {
    init() {

        this.sliderInstance = tns({
            container: this.el,
            items: 1,
            slideBy: 'page',
            nav: false,
            controls: false,
            loop: true,
            mouseDrag: true,
            autoplay: true,
            autoplayTimeout: 2500,
            autoplayButtonOutput: false,
        });
    }
}
