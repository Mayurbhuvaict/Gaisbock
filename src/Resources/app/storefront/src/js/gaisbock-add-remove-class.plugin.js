import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockAddRemoveClass extends Plugin {
    init() {
        this._gaisbockAddRemoveClass();
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
        const parentDiv = document.querySelector('.header-main');
        const searchButton = document.querySelector('.gaisbock-search-button-for-css');

        searchButton.addEventListener('click',()=>{
            if(parentDiv.classList.contains('search-click') === false){
                parentDiv.classList.add('search-click');
            }else{
                parentDiv.classList.remove('search-click');
            }

        });

        function toggleHoverClass(event) {
            if (event.type === 'mouseenter') {
                // Add the class "gaisbock-hover" when the mouse enters the element
                getClass.classList.add("gaisbock-hover");
            } else if (event.type === 'mouseleave') {
                // Remove the class "gaisbock-hover" when the mouse leaves the element
                getClass.classList.remove("gaisbock-hover");
            }
        }
    }
}