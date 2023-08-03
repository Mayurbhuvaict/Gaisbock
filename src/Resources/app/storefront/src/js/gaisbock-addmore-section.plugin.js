import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockAddmoreSection extends Plugin {
    init() {
        this._gaisbockAddMore();
    }

    _gaisbockAddMore() {
        let language = document.querySelector('#readMore').getAttribute('data-language-value');
        var toggleButton = document.getElementById("readMore");
        var textDiv = document.getElementById("gaisbock-addmore-section");
        toggleButton.addEventListener("click", function () {
            if(language === "de-DE"){
                toggleButton.textContent = toggleButton.textContent === "Mehr lesen" ? "Weniger lesen" : "Mehr lesen";
                textDiv.style.display = textDiv.style.display === "block" ? "none" : "block";
            } else {
                toggleButton.textContent = toggleButton.textContent === "Read More" ? "Read Less" : "Read More";
                textDiv.style.display = textDiv.style.display === "block" ? "none" : "block";
            }
        });
    }
}