import Plugin from 'src/plugin-system/plugin.class';

export default class SeoFaqElement extends Plugin {
    init() {
        let onHrefClick = function(el) {
            //stop routing to /faq/{question}
            el.preventDefault();
            //toggle --active
            let href = el.target.href;
            let cmsId = el.target.id;
            let targetID = cmsId.replace( /^link_/g , '');
            targetID = targetID.replace( /^minus_/g , '');
            targetID = targetID.replace( /^plus_/g , '');
            let currentQuestion = document.getElementById(targetID);
            const currentPlus = document.getElementById( 'plus_' + targetID);
            const currentMinus = document.getElementById( 'minus_' + targetID);

            if(currentQuestion && currentQuestion.className.includes("--active")) {
                currentQuestion.className =
                    currentQuestion.className.replace( /(?:^|\s)--active(?!\S)/g , '');
                currentMinus.className =
                    currentMinus.className.replace( /(?:^|\s)--active(?!\S)/g , '');
                currentPlus.className += " --active";
            } else {
                currentQuestion.className += " --active";
                currentPlus.className =
                    currentPlus.className.replace( /(?:^|\s)--active(?!\S)/g , '');
                currentMinus.className += " --active";
            }
        };

        //addEventListener for every cms element question
        let faqHref = document.getElementsByClassName("cms-faq-link");
        for (let i = 0; i < faqHref.length; i++) {
            faqHref[i].addEventListener("click", onHrefClick);
        }
    }
}
