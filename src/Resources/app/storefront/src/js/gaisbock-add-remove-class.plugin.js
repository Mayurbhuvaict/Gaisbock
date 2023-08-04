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


        let getEleId = document.getElementById('navigationId');
        let getClass = document.querySelector('.gaisbock-header-main');
        getEleId.addEventListener('mouseenter', toggleHoverClass);
        getEleId.addEventListener('mouseleave', toggleHoverClass);

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