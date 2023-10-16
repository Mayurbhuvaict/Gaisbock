import Plugin from 'src/plugin-system/plugin.class';
import CookieStorage from 'src/helper/storage/cookie-storage.helper';
import HttpClient from 'src/service/http-client.service';
import PseudoModalUtil from 'src/utility/modal-extension/pseudo-modal.util';

export default class LanguageDetectorPlugin extends Plugin {
    init() {
        this.httpClient = new HttpClient();

        this.setCookie = true;
        this.checkLanguage();

        this.targetLanguage = null;
        this.languages      = {};
    }

    checkLanguage() {
        if ("redirect-accepted" === CookieStorage.getItem("language-detector-redirect")
            || "redirect-declined" === CookieStorage.getItem("language-detector-redirect")) {
            return;
        }

        const request = this.httpClient.get(
            window.router['frontend.language_detector.check_language'],
            (response) => {
                if (request.status === 200) {
                    response = JSON.parse(response);

                    this.setCookie = response.setCookie;

                    if (!response.success) {
                        return; // do nothing
                    }

                    this.processResponse(
                        response.data,
                        response.html
                    );
                } else {
                    // do nothing
                }
            }
        );
    }

    processResponse(data, html) {
        this.targetLanguage = data.targetLanguage;
        this.languages      = data.languages;

        (new PseudoModalUtil(html)).open();

        this.registerEvents();
    }

    registerEvents() {
        const $declineBtn = document.getElementById('languageDetectorDecline');
        const $acceptBtn  = document.getElementById('languageDetectorAccept');

        $declineBtn.addEventListener('click', this.onLanguageDetectorDecline.bind(this));
        $acceptBtn.addEventListener('click', this.onLanguageDetectorAccept.bind(this));

        const languages = Object.values(this.languages);

        languages.forEach(language => {
            const $languageBtn = document.getElementById('languageDetectorLang-' + language.id);

            $languageBtn.addEventListener('click', this.onLanguageDetectorSwitch.bind(this));
        });
    }

    onLanguageDetectorDecline() {
        if (this.setCookie) {
            CookieStorage.setItem('language-detector-redirect', 'redirect-declined');
        }
    }

    onLanguageDetectorAccept() {
        if (this.setCookie) {
            CookieStorage.setItem('language-detector-redirect', 'redirect-accepted');
        }
    }

    onLanguageDetectorSwitch(event) {
        const languageId = event.target.value;
        const language   = this.languages[languageId];

        document.querySelector('.neti-language-detector-title').innerHTML = language.headline;
        document.querySelector('.neti-language-detector-text').innerHTML  = language.text;
        document.getElementById('languageDetectorAccept').innerHTML      = language.buttonAccept;
        document.getElementById('languageDetectorDecline').innerHTML      = language.buttonDecline;
        document.getElementById('ld-modal-lang-name').innerHTML           = language.name;

        let newLocale     = language.locale.split('-');
        let countryClass  = 'country-' + newLocale[0];
        let languageClass = 'language-' + newLocale[1];
        let classes       = document.getElementById('ld-modal-lang-flag').classList;

        classes.remove(classes[2], classes[3]);
        classes.add(countryClass, languageClass);
    }
}
