import Plugin from 'src/plugin-system/plugin.class';
export default class HlStoreSurveyPlugin extends Plugin {

    init() {

        window.onload = function() {

            let myModal = new bootstrap.Modal(
                document.getElementById("hlShopExperienceModal")
            );
            myModal.show();
            // Declare initial rating
            var initialRating = 5;

            // Declare emotions array
            var emotionsArray = ['angry', 'disappointed', 'meh', 'happy', 'inLove'];

            // Find the element with class 'hl-shop-experience-modal' and its child element with id 'emotionContainer'
            var emotionContainer = document.querySelector('.hl-shop-experience-modal #emotionContainer');

            // Create emotions rating widget using the 'emotionsRating' function
            EmotionsRating(emotionContainer, {
                emotionSize: 30,
                bgEmotion: 'happy',
                inputName: 'shopExperienceRating',
                emotions: emotionsArray,
                color: '#ffbf00',
                initialRating: initialRating
            });

            // Set the value of the input field with class 'emoji-rating-emotion-container-emotionContainer' to the initial rating
            document.querySelector('.emoji-rating-emotion-container-emotionContainer').value = initialRating;

        };
    }
}
