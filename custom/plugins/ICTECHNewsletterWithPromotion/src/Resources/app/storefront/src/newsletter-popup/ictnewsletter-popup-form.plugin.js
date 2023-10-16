import Plugin from 'src/plugin-system/plugin.class';
import HttpClient from 'src/service/http-client.service';

export default class IctNewsletterPopupForm extends Plugin {
    static options = {
        // let captcha plugins know, we are an ajax form
        useAjax: true,
    };

    init() {
        if (this.el.nodeName !== 'FORM') {
            throw new Error('Plugin NewsletterPopupForm must be initialized on an element of type FORM');
        }

        this._formElement = this.el;
        this._popupParent = this.el.closest('[data-newsletter-popup]');

        if (!this._popupParent) {
            return;
        }

        this._errorContainer = this._popupParent.querySelector('.ict-newsletter-popup--error-container');
        this._captchaErrorContainer = this._popupParent.querySelector('.ict-newsletter-popup--captcha-error-container');
        this._submitButton = this._formElement.querySelector('button[type="submit"]');
        this._httpClient = new HttpClient();

        this._isSubmitting = false;

        this._formElement.addEventListener('submit', (event) => {
            event.preventDefault();
            this.submitForm();
        });
    }

    submitForm() {
        if (this._isSubmitting) { return; }

        if (this._formElement.checkValidity() === false) { return; }

        const input = new FormData(this._formElement);

        if (!input.get('email') || !input.get('privacy')) { return; }

        const serializedData = {};
        for (let [k, v] of input.entries()) {
            serializedData[k] = v;
        }

        this._isSubmitting = true;

        try {
            this._httpClient.post(
                this._formElement.action,
                JSON.stringify({
                    ...serializedData,
                    option: 'subscribe',
                }),
                (response) => {
                    let parsedResponse;

                    try {
                        parsedResponse = JSON.parse(response);
                    } catch(e) { }

                    const innerContent = this._popupParent.querySelector('.ict-newsletter-popup--content-inner');

                    const responseWrapper = this._popupParent.querySelector('.ict-newsletter-popup--response-wrapper');
                    const responseText = responseWrapper.querySelector('.ict-newsletter-popup--response-text');

                    if (
                        Array.isArray(parsedResponse) &&
                        parsedResponse.length &&
                        parsedResponse[0] &&
                        parsedResponse[0].alert
                    ) {
                        const message = parsedResponse[0].alert;
                        const errorCode = parsedResponse[0].error;
                        const isErrorMessage = parsedResponse[0].type !== 'success';

                        this._errorContainer.innerHTML = '';
                        this._captchaErrorContainer.innerHTML = '';

                        if (errorCode) {
                            if (errorCode === 'invalid_captcha') {
                                this._captchaErrorContainer.innerHTML = message;
                            } else {
                                this._errorContainer.innerHTML = message;
                            }
                        } else if (isErrorMessage) {
                            this._errorContainer.innerHTML = message;
                        } else {
                            // success
                            responseText.textContent = message;
                            innerContent.classList.add('hide');
                            responseWrapper.classList.add('open');
                        }
                    } else {
                        this._errorContainer.innerHTML = `
                                <div role="alert" class="alert-danger">
                                    <div class="alert-content-container">
                                        <div class="alert-content">Unknown Error. Please contact the shop support.</div>
                                    </div>
                                </div>
                            `;
                    }

                    this._isSubmitting = false;
                }
            );
        } catch (e) {
            console.error(e);
            this._isSubmitting = false;
        }
    }

    // This may get called by the shopware recaptcha plugins
    // to let us know we are ready to submit
    sendAjaxFormSubmit() {
        this.submitForm();
    }
}
