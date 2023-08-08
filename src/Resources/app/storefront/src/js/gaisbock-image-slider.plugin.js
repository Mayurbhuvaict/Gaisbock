import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockimageslider extends Plugin {
    init() {
        var classname = document.getElementById('gaisbock-slider');
        console.log(classname)
        if (classname !== null) {
            var slider = {
                slider: {
                    navPosition: 'bottom',
                    speed: 300,
                    autoplayTimeout: 5000,
                    autoplay: false,
                    autoplayButtonOutput: false,
                    nav: true,
                    controls: true,
                    autoHeight: true
                }
            };
            classname.setAttribute('data-base-slider-options',JSON.stringify(slider));
        }
    }
}