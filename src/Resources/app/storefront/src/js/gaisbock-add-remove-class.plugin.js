import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockAddRemoveClass extends Plugin {
    init() {
        this._gaisbockAddRemoveClass();
    }

    _gaisbockAddRemoveClass() {
        let stockOpen = document.getElementById('navigationId');
        stockOpen.addEventListener('mouseenter', toggleHoverClass);
        stockOpen.addEventListener('mouseleave', toggleHoverClass);

        function toggleHoverClass(event) {
            if (event.type === 'mouseenter') {
                // Add the class "gaisbock-hover" when the mouse enters the element
                stockOpen.classList.add("gaisbock-hover");
            } else if (event.type === 'mouseleave') {
                // Remove the class "gaisbock-hover" when the mouse leaves the element
                stockOpen.classList.remove("gaisbock-hover");
            }
        }
    }
}