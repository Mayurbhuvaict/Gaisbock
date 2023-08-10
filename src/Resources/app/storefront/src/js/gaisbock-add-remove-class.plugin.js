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
        const parentDiv = document.querySelector('.header-main');
        const searchButton = document.querySelector('.gaisbock-search-button-for-css');

        searchButton.addEventListener('click',()=>{
            if(parentDiv.classList.contains('search-click') === false){
                parentDiv.classList.add('search-click');
            }else{
                parentDiv.classList.remove('search-click');
            }
        });

    }

    _gaisbockCloseOfcanvasRemoveClass(){
        const closeBotton = document.querySelector('.gaisbock-close');
        const mainNavClass = document.querySelector('.hovered');
        document.addEventListener('DOMContentLoaded', function() {
            closeBotton.addEventListener('click',function() {
                console.log('hellooooo');
                mainNavClass.classList.remove('hovered');
            });
        });
    }
}