import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockImageWithAddmoreSlider extends Plugin {
    init() {
        this._gaisbockAddMoreWithSlider();
    }

    _gaisbockAddMoreWithSlider(){
        let language = document.querySelector("#readMoreSlider") ? document.querySelector("#readMoreSlider").getAttribute("data-language-value") : null;
        var toggleButton = document.querySelectorAll(".readmore-btn-slider");
        if(toggleButton !== null) {
            toggleButton.forEach((button) => {
                const id = button.getAttribute("data-diffrent");
                var textDiv = document.getElementById("gaisbock-custom-text-image-slider-"+id);
                button.addEventListener("click", function () {
                    if(textDiv.classList.contains('visibility'))
                    {
                        textDiv.classList.remove('visibility');
                    }
                    else
                    {
                        textDiv.classList.add('visibility');
                    }
                    if (button.classList.contains('active'))
                    {
                        button.classList.remove('active');
                    }else{
                        button.classList.add('active');
                    }
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