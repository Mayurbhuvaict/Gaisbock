import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockAddmoreSection extends Plugin {
    init() {
        this._gaisbockAddMore();
    }

    _gaisbockAddMore() {

        let language = document.querySelector(".readmore-btn") ? document.querySelector(".readmore-btn").getAttribute("data-language-value") : null;
        var toggleButton = document.querySelectorAll(".readmore-btn");

        if(toggleButton !== null) {
            toggleButton.forEach((button) => {
                const id = button.getAttribute("data-diffrent");
                var textDiv = document.getElementById("gaisbock-addmore-section-"+id);
                button.addEventListener("click", function () {
                    textDiv.style.display = textDiv.style.display === "block" ? "none" : "block";
                    if (language === "de-DE") {
                        button.textContent = button.textContent === "Mehr lesen" ? "Weniger lesen" : "Mehr lesen";
                    } else if(language === "en-GB") {
                        button.textContent = button.textContent === "Read More" ? "Read Less" : "Read More";
                    }else {
                        button.textContent = button.textContent === "Lire la suite" ? "Lire moins" : "Lire la suite";
                    }
                });
            });
        }
    }
}