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
        // let stockOpen = document.getElementsByClassName('main-navigation-link');
        // stockOpen.addEventListener('mouseenter', toggleHoverClass);
        // stockOpen.addEventListener('mouseleave', toggleHoverClass);

        // function toggleHoverClass(event) {
        //     if (event.type === 'mouseenter') {
        //         // Add the class "gaisbock-hover" when the mouse enters the element
        //         stockOpen.classList.add("gaisbock-hover");
        //     } else if (event.type === 'mouseleave') {
        //         // Remove the class "gaisbock-hover" when the mouse leaves the element
        //         stockOpen.classList.remove("gaisbock-hover");
        //     }
        // }
        
        const parentDiv = document.querySelector('.header-main');
        
        const hoverElement = document.querySelectorAll('.is-open');
        console.log(hoverElement);
        // Add event listener for mouseenter to add the 'active' class
        hoverElement.addEventListener('mouseenter', () => {
          parentDiv.classList.add('hovered');
        });
      
        // // Add event listener for mouseleave to remove the 'active' class
        hoverElement.addEventListener('mouseleave', () => {
          parentDiv.classList.remove('hovered');
        });
        
    }
}