import Plugin from 'src/plugin-system/plugin.class';

export default class SeoFaq extends Plugin {
    init() {
        //get /faq/{question} variable to show correct answer
        const href = window.location.href;
        const currentPage = href.substring(href.lastIndexOf('/') + 1);

        let setQuestionActive = function(id) {
            //toggle --active and scroll to current question
            let currentQuestion = document.getElementById(id);
            if(currentQuestion && currentQuestion.className.includes("--active")) {
                currentQuestion.className =
                    currentQuestion.className.replace( /(?:^|\s)--active(?!\S)/g , '');
            } else {
                currentQuestion.className += " --active";
                document.getElementById(id + '_box').scrollIntoView({
                    behavior: 'smooth'
                });
                document.getElementById( id + '_plus').className =
                    document.getElementById( id + '_plus').className.replace( /(?:^|\s)--active(?!\S)/g , '');
                document.getElementById( id + '_minus').className += ' --active';
            }
        };

        if(currentPage != "faq") {
            setQuestionActive(currentPage);
        }
    }
}
