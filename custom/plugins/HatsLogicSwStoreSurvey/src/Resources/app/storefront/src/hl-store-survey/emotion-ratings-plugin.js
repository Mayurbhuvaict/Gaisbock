import Plugin from 'src/plugin-system/plugin.class';
export default class hlEmotionRating extends Plugin {
    init() {
        (function(document, window) {
            var defaults = {
                window: window,
                document: document,
            }
             // Default options for the plugin as a simple object
            defaults = {
                bgEmotion: 'happy',
                count: 5,
                color: '#d0a658;',
                emotionSize: 30,
                inputName: 'ratings[]',
                emotionOnUpdate: null,
                ratingCode: 5,
                disabled: false,
                useCustomEmotions: false,
                transformImages: false,
            };
            //the collection of emotions to show on the ratings
            var emotionsArray = {
                angry: '&#x1F620;',
                disappointed: '&#x1F61E;',
                meh: '&#x1F610;',
                happy: '&#x1F60A;',
                smile: '&#x1F603;',
                wink: '&#x1F609;',
                laughing: '&#x1F606;',
                inlove: '&#x1F60D;',
                heart: '&#x2764;',
                crying: '&#x1F622;',
                star: '&#x2B50;',
                poop: '&#x1F4A9;',
                cat: '&#x1F63A;',
                like: '&#x1F44D;',
                dislike: '&#x1F44E;',
            };
            let element;
            let containerCode;
            let styleCode;
            let settings;
            let code;
            let clicked = [];
            const EmotionsRating = function(emotionContainer, options) {
                settings = Object.assign({}, defaults, options);
                element=emotionContainer;
                containerCode = element.getAttribute('id');
                styleCode = 'emotion-style-' + containerCode;
                containerCode = 'emotion-container-' + containerCode;
                code = 'emoji-rating-' + containerCode;
                clicked = [];
                clicked[containerCode] = false;
                init();
            };
            const init =  function() {
                emotionStyle();
                renderEmotion();
                manageClick();
            };
            const emotionStyle = function() {
                const style = document.createElement('style');
                style.textContent = '.' + styleCode + '{margin-right:3px;border-radius: 50%;cursor:pointer;opacity:0.3;display: inline-block;font-size:' + defaults.emotionSize + 'px; text-decoration:none;line-height:0.9;text-align: center;color:' + defaults.color + '}';
                element.appendChild(style);
            };
            const renderEmotion = function() {
                const count = settings.count;
                const useCustomEmotions = settings.useCustomEmotions;
                let bgEmotion = emotionsArray[settings.bgEmotion];
                
                if (useCustomEmotions) {
                  bgEmotion = `<img src="${settings.bgEmotion}" class="custom-${styleCode}">`;
                }
                
                const container = document.createElement('div');
                container.classList.add(containerCode);
                
                for (let i = 1; i <= count; i++) {
                  const emotionDiv = document.createElement('div');
                  emotionDiv.classList.add(styleCode);
                  emotionDiv.dataset.index = i;
                  emotionDiv.innerHTML = bgEmotion;
                  container.appendChild(emotionDiv);
                }
              
                element.appendChild(container); // Append the container to the specified element
                if (settings.initialRating > 0) {
                    initalRate(settings.initialRating);
                } else {
                    appendInput();
                }
                
              };
              
            const clearEmotion = function(content) {
                if (!settings.disabled) {
                    var useCustomEmotions = settings.useCustomEmotions;
                    var bgEmotion = emotionsArray[content];
                    if (useCustomEmotions) {
                        bgEmotion = '<img src=\'' + settings.bgEmotion + '\' class=\'custom-' + styleCode + '\'>';
                    }
                    const emotionElements = element.querySelectorAll(`.${styleCode}`);
                    emotionElements.forEach((emotionElement) => {
                        emotionElement.style.opacity = 0.3;
                        emotionElement.innerHTML = bgEmotion;
                    });

                }
            };
            const showEmotion= function(count) {
                clearEmotion(settings.bgEmotion);
                var useCustomEmotions = settings.useCustomEmotions;
                var emotion = getEmotion(settings.emotions, count, useCustomEmotions);
                if (useCustomEmotions) {
                    var emotionSrc = settings.emotions[emotion];
                    var emotionHTML = '<img src=\'' + emotionSrc + '\' class=\'custom-' + styleCode + '\'>';
                    emotion = emotionHTML;
                }
                for (var i = 0; i < count; i++) {
                    var currentElements = element.querySelectorAll('.' + styleCode);
                    var currentElement = currentElements[i];
                    currentElement.style.opacity = 1;
                    if(emotion){
                        currentElement.innerHTML = emotion;
                    }
                }
                  
            };
            const manageClick = function() {
                if (!settings.disabled) {
                    element.addEventListener('click', function(event) {
                        if (event.target.classList.contains(styleCode)) {
                            var index = event.target.dataset.index;
                            var count = parseInt(index, 10);
                            showEmotion(count);
                            count = count;
                            if (!clicked[containerCode]) {
                                updateInput(count);
                                clicked[containerCode] = true;
                            } else {
                                updateInput(count);
                            }
                            if (typeof settings.onUpdate === 'function') {
                                settings.onUpdate.call(this, count);
                            }
                        }
                    }.bind(this));                    
                }
            };
            const initalRate = function(count) {
                showEmotion(count);
                if (!clicked[containerCode]) {
                    appendInput(count);
                    clicked[containerCode] = true;
                }
            };
            const updateInput = function(count) {
                var _input = element.querySelector('.' +code);
                _input.value=count;
            };

            const appendInput = function(count) {
                var total = '';
                if (!count) {
                    total = count;
                }
                var input = document.createElement('input');
                input.type = 'hidden';
                input.className = code;
                input.name = settings.inputName;
                input.value = total;
              
                var div = element;
                div.appendChild(input);
            };
            const getEmotion = function(_emotions, count, onlyIndex = false) {
                var emotion;
                var emotionsLength = _emotions.length;
                if (emotionsLength == 1) {
                    emotion = onlyIndex ? 0 : emotionsArray[_emotions[0]];
                } else {
                    var emotionIndex = (count - 1);
                    emotion = onlyIndex ? (emotionIndex > (emotionsLength - 1)) ? (emotionsLength - 1) : emotionIndex : emotionsArray[_emotions[count - 1]];
                }
                return emotion;
            }
            window.EmotionsRating = EmotionsRating;
        })(document, window);
    }
}
