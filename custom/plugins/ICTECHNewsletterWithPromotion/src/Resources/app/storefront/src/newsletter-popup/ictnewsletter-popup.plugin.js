import Plugin from 'src/plugin-system/plugin.class';
import setBoolSessionValue from "../utils/setBoolSessionValue";
import setBoolLocalValue from "../utils/setBoolLocalValue";

export default class NewsletterPopup extends Plugin {
    init() {
        this._popupId = this.el.getAttribute('data-popup-id');
        const devMode = this.el.getAttribute('data-dev-mode');
        this._storageType = this.el.getAttribute('data-storage-type');

        if (!this._popupId) {
            return;
        }

        this._popupClosedStorageName = `${this._popupId}-closed`;

        let wasClosed = false;
        if (this._storageType === 'sessionStorage') {
            wasClosed = sessionStorage.getItem(this._popupClosedStorageName);
        } else {
            wasClosed = localStorage.getItem(this._popupClosedStorageName);
        }

        if (wasClosed && !devMode) {
            return;
        }

        const trigger = this.el.getAttribute('data-popup-trigger');
        const time = this.el.getAttribute('data-popup-time');
        const triggerTime = `${time}000`;

        if (trigger === 'time') {
            this.openPopupByTime = this.openPopupByTime.bind(this);
            window.setTimeout(this.openPopupByTime, triggerTime)
        } else {
            this.openPopupByScroll = this.openPopupByScroll.bind(this);
            window.addEventListener('scroll', this.openPopupByScroll)
        }

        const closeBtn = this.el.querySelector('.ict-newsletter-popup--close-btn');
        const nonSubscribeBtn = this.el.querySelector('.ict-newsletter-popup--non-subscribe-btn')
        const bgLayer = document.querySelector('.ict-newsletter-popup--bg-layer');

        this.closePopup = this.closePopup.bind(this);
        closeBtn.addEventListener('click', this.closePopup);
        nonSubscribeBtn.addEventListener('click', this.closePopup);
        bgLayer.addEventListener('click', this.closePopup);
    }

    openPopupByTime() {
        this.openPopup()
    }

    openPopupByScroll() {
        const scrollTrigger = this.el.getAttribute('data-popup-scroll');

        if (window.scrollY >= scrollTrigger) {

            if (this.el.classList.contains('opened')) {
                return null;
            } else {
                this.openPopup();

                //set flag if popup opened one time
                this.el.classList.add('opened')
            }
        }
    }

    // Open popup
    openPopup() {
        const popup = this.el;
        const bgLayer = document.querySelector('.ict-newsletter-popup--bg-layer');

        bgLayer.classList.add("open");
        popup.classList.add("open");
    }

    // Close popup
    closePopup() {
        const popup = this.el;
        popup.classList.remove("open");

        const bgLayer = document.querySelector('.ict-newsletter-popup--bg-layer');
        bgLayer.classList.remove("open");

        if (this._popupId) {
            if (this._storageType === "sessionStorage") {
                setBoolSessionValue(this._popupClosedStorageName);
            } else {
                setBoolLocalValue(this._popupClosedStorageName);
            }
        }
    }
}
