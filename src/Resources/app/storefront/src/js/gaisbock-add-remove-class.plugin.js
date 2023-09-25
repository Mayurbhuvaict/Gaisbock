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
        const header = document.querySelector(".gaisbock-header-main");
        const extraDiv = document.querySelector(".main-navigation-menu");
        const toggleClass = "is-sticky";

        window.addEventListener("scroll", () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 150) {
                header.classList.add(toggleClass);
            } else {
                header.classList.remove(toggleClass);
            }
        });
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