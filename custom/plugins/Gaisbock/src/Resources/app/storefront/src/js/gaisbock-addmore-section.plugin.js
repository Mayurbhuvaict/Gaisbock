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
                    if (button.classList.contains('active'))
                    {
                        button.classList.remove('active');
                    }else{
                        button.classList.add('active');
                    }
                    if (language === "de-DE") {
                        button.textContent = button.textContent === "Mehr erfahren" ? "Weniger lesen" : "Mehr erfahren";
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