import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockAddRemoveClass extends Plugin {
    init() {
        this.navClassChangeHeader = document.getElementById("mainNavigationHover");
        this.navClassChangeMainDiv = document.getElementById("mainNavigationHoverMainClass");
        this.navigationFlyout = document.getElementById("navigationFlyoutId");
        this._gaisbockAddRemoveClass();
        this._gaisbockCloseOfcanvasRemoveClass();
    }

    _gaisbockAddRemoveClass() {

        // const header = document.querySelector(".gaisbock-header-main");
        // const extraDiv = document.querySelector(".main-navigation-menu");
        // // Variable to track the scroll direction
        // let lastScrollTop = 200;
        // let shouldApplyClasses = false;
        // // Function to handle the scroll event
        // function handleScroll() {
        //     const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        //
        //     // Check if user has scrolled at least 200 pixels from the top
        //     if (scrollTop >= 200) {
        //         shouldApplyClasses = true;
        //     } else {
        //         shouldApplyClasses = false;
        //     }
        //
        //     if (shouldApplyClasses) {
        //         if (scrollTop > lastScrollTop) {
        //             // Scrolling down
        //             header.classList.remove('show-on-scroll');
        //             header.classList.add('is-sticky','hide-on-scroll');
        //         } else {
        //             // Scrolling up
        //             header.classList.remove('hide-on-scroll');
        //             header.classList.add('is-sticky','show-on-scroll');
        //         }
        //     } else {
        //         // Reset all classes when not scrolled 200 pixels
        //         header.classList.remove('is-sticky', 'is-fixed', 'is-scrolling', 'hide-on-scroll', 'show-on-scroll');
        //     }
        //
        //     lastScrollTop = scrollTop;
        // }
        //
        // // Attach the scroll event listener
        // window.addEventListener('scroll', handleScroll);
    }

    _gaisbockCloseOfcanvasRemoveClass(){
        const closeBotton = document.querySelector('.gaisbock-close');
        const mainNavClass = document.querySelector('.hovered');
        document.addEventListener('DOMContentLoaded', function() {
            closeBotton.addEventListener('click',function() {
                mainNavClass.classList.remove('hovered');
            });
        });
    }
}