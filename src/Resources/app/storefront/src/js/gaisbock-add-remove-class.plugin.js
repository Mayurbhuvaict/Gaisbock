import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockAddRemoveClass extends Plugin {
    init() {
        this.navClassChangeHeader = document.getElementById("mainNavigationHover");
        this.navClassChangeMainDiv = document.getElementById("mainNavigationHoverMainClass");
        this._gaisbockAddRemoveClass();
        this._classRemoveAdd();
    }

    _gaisbockAddRemoveClass() {
        const header = document.querySelector(".gaisbock-header-main");
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

    _classRemoveAdd(){

        this.navClassChangeHeader.addEventListener('mouseover', event =>{
            var navigationIdElement = document.getElementsByClassName("navigation-flyout");
            let isMain_files = navigationIdElement[0].classList.contains("is-open");
            if (isMain_files) {
                let headerMainElement = document.getElementsByClassName("gaisbock-header-main");
                headerMainElement[0].classList.add("hovered");
            } else {
            }
        });

        this.navClassChangeHeader.addEventListener('mouseout', event =>{

            var navigationIdElement = document.getElementsByClassName("navigation-flyout");
            let isMain_files = navigationIdElement[0].classList.contains("main-navigation-link");
            if (isMain_files) {

            } else {
                let headerMainElement = document.getElementsByClassName("gaisbock-header-main");
                headerMainElement[0].classList.remove("hovered");
            }

        });

        this.navClassChangeMainDiv.addEventListener('mouseout', event =>{

            var navigationIdElement = document.getElementsByClassName("navigation-flyout");
            let isMain_files = navigationIdElement[0].classList.contains("main-navigation-link");

            if (isMain_files) {

            } else {
                let headerMainElement = document.getElementsByClassName("gaisbock-header-main");
                headerMainElement[0].classList.remove("hovered");
            }

        });
    }
}