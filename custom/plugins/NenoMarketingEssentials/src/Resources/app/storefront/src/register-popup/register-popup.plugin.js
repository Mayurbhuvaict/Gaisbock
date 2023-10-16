import Plugin from 'src/plugin-system/plugin.class';
import setBoolSessionValue from "../utils/setBoolSessionValue";
import setBoolLocalValue from "../utils/setBoolLocalValue";

export default class RegisterPopup extends Plugin {
    init() {
        this._popupId = this.el.getAttribute('data-popup-id');
        this._storageType = this.el.getAttribute('data-storage-type');
        const devMode = this.el.getAttribute('data-dev-mode');

        if (!this._popupId) {
            return;
        }

        this._popupClosedStorageName = `${this._popupId}-closed`;

        console.log("STORAGE TYPE: ", this._storageType);

        let wasClosed = false;
        if (this._storageType === 'sessionStorage') {
            wasClosed = sessionStorage.getItem(this._popupClosedStorageName);
        } else {
            wasClosed = localStorage.getItem(this._popupClosedStorageName);
        }

        if (wasClosed && !devMode) {
            return;
        }

        const trigger = this.el.getAttribute('data-register-popup-trigger');
        const time = this.el.getAttribute('data-register-popup-time');
        const triggerTime = `${time}000`;

        if (trigger === 'time') {
            this.openPopupByTime = this.openPopupByTime.bind(this);
            window.setTimeout(this.openPopupByTime, triggerTime)
        } else {
            this.openPopupByScroll = this.openPopupByScroll.bind(this);
            window.addEventListener('scroll', this.openPopupByScroll)
        }

        const closeBtn = this.el.querySelector('.nme-register-popup--close-btn');
        const nonSubmitBtn = this.el.querySelector('.nme-register-popup--non-submit-btn')
        const bgLayer = document.querySelector('.nme-register-popup--bg-layer');

        this.closePopup = this.closePopup.bind(this);
        closeBtn.addEventListener('click', this.closePopup);
        nonSubmitBtn.addEventListener('click', this.closePopup);
        bgLayer.addEventListener('click', this.closePopup);

    }

    openPopupByTime() {
        this.openPopup()
    }

    openPopupByScroll() {
        const scrollTrigger = this.el.getAttribute('data-register-popup-scroll');
        console.log(window.scrollY);

        if (window.scrollY >= scrollTrigger) {
            this.openPopup();
        }
    }

    // Open popup
    openPopup() {
        const popup = this.el;
        const bgLayer = document.querySelector('.nme-register-popup--bg-layer');

        bgLayer.classList.add("open");
        popup.classList.add("open");
    }

    // Close popup
    closePopup() {
        const popup = this.el;
        popup.classList.remove("open");

        const bgLayer = document.querySelector('.nme-register-popup--bg-layer');
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
