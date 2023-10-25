"use strict";(self.webpackChunk=self.webpackChunk||[]).push([["neno-marketing-essentials"],{5690:(e,t,i)=>{var s=i(6285);const o=e=>{sessionStorage.setItem(e,"1")},r=e=>{localStorage.setItem(e,"1")};class n extends s.Z{init(){this._popupId=this.el.getAttribute("data-popup-id");const e=this.el.getAttribute("data-dev-mode");if(this._storageType=this.el.getAttribute("data-storage-type"),!this._popupId)return;this._popupClosedStorageName=`${this._popupId}-closed`;let t=!1;if(t="sessionStorage"===this._storageType?sessionStorage.getItem(this._popupClosedStorageName):localStorage.getItem(this._popupClosedStorageName),t&&!e)return;const i=this.el.getAttribute("data-popup-trigger"),s=`${this.el.getAttribute("data-popup-time")}000`;"time"===i?(this.openPopupByTime=this.openPopupByTime.bind(this),window.setTimeout(this.openPopupByTime,s)):(this.openPopupByScroll=this.openPopupByScroll.bind(this),window.addEventListener("scroll",this.openPopupByScroll));const o=this.el.querySelector(".nme-newsletter-popup--close-btn"),r=this.el.querySelector(".nme-newsletter-popup--non-subscribe-btn"),n=document.querySelector(".nme-newsletter-popup--bg-layer");this.closePopup=this.closePopup.bind(this),o.addEventListener("click",this.closePopup),r.addEventListener("click",this.closePopup),n.addEventListener("click",this.closePopup)}openPopupByTime(){this.openPopup()}openPopupByScroll(){const e=this.el.getAttribute("data-popup-scroll");if(window.scrollY>=e){if(this.el.classList.contains("opened"))return null;this.openPopup(),this.el.classList.add("opened")}}openPopup(){const e=this.el;document.querySelector(".nme-newsletter-popup--bg-layer").classList.add("open"),e.classList.add("open")}closePopup(){this.el.classList.remove("open");document.querySelector(".nme-newsletter-popup--bg-layer").classList.remove("open"),this._popupId&&("sessionStorage"===this._storageType?o(this._popupClosedStorageName):r(this._popupClosedStorageName))}}var p,a,l,u=i(8254);class h extends s.Z{init(){if("FORM"!==this.el.nodeName)throw new Error("Plugin NewsletterPopupForm must be initialized on an element of type FORM");this._formElement=this.el,this._popupParent=this.el.closest("[data-newsletter-popup]"),this._popupParent&&(this._errorContainer=this._popupParent.querySelector(".nme-newsletter-popup--error-container"),this._captchaErrorContainer=this._popupParent.querySelector(".nme-newsletter-popup--captcha-error-container"),this._submitButton=this._formElement.querySelector('button[type="submit"]'),this._httpClient=new u.Z,this._isSubmitting=!1,this._formElement.addEventListener("submit",(e=>{e.preventDefault(),this.submitForm()})))}submitForm(){if(this._isSubmitting)return;if(!1===this._formElement.checkValidity())return;const e=new FormData(this._formElement);if(!e.get("email")||!e.get("privacy"))return;const t={};for(let[i,s]of e.entries())t[i]=s;this._isSubmitting=!0;try{this._httpClient.post(this._formElement.action,JSON.stringify({...t,option:"subscribe"}),(e=>{let t;try{t=JSON.parse(e)}catch(e){}const i=this._popupParent.querySelector(".nme-newsletter-popup--content-inner"),s=this._popupParent.querySelector(".nme-newsletter-popup--response-wrapper"),o=s.querySelector(".nme-newsletter-popup--response-text");if(Array.isArray(t)&&t.length&&t[0]&&t[0].alert){const e=t[0].alert,r=t[0].error,n="success"!==t[0].type;this._errorContainer.innerHTML="",this._captchaErrorContainer.innerHTML="",r?"invalid_captcha"===r?this._captchaErrorContainer.innerHTML=e:this._errorContainer.innerHTML=e:n?this._errorContainer.innerHTML=e:(o.textContent=e,i.classList.add("hide"),s.classList.add("open"))}else this._errorContainer.innerHTML='\n                                <div role="alert" class="alert-danger">\n                                    <div class="alert-content-container">\n                                        <div class="alert-content">Unknown Error. Please contact the shop support.</div>\n                                    </div>\n                                </div>\n                            ';this._isSubmitting=!1}))}catch(e){console.error(e),this._isSubmitting=!1}}sendAjaxFormSubmit(){this.submitForm()}}p=h,l={useAjax:!0},(a=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var i=e[Symbol.toPrimitive];if(void 0!==i){var s=i.call(e,t||"default");if("object"!=typeof s)return s;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(a="options"))in p?Object.defineProperty(p,a,{value:l,enumerable:!0,configurable:!0,writable:!0}):p[a]=l;class c extends s.Z{init(){this._popupId=this.el.getAttribute("data-popup-id"),this._storageType=this.el.getAttribute("data-storage-type");const e=this.el.getAttribute("data-dev-mode");if(!this._popupId)return;this._popupClosedStorageName=`${this._popupId}-closed`,console.log("STORAGE TYPE: ",this._storageType);let t=!1;if(t="sessionStorage"===this._storageType?sessionStorage.getItem(this._popupClosedStorageName):localStorage.getItem(this._popupClosedStorageName),t&&!e)return;const i=this.el.getAttribute("data-register-popup-trigger"),s=`${this.el.getAttribute("data-register-popup-time")}000`;"time"===i?(this.openPopupByTime=this.openPopupByTime.bind(this),window.setTimeout(this.openPopupByTime,s)):(this.openPopupByScroll=this.openPopupByScroll.bind(this),window.addEventListener("scroll",this.openPopupByScroll));const o=this.el.querySelector(".nme-register-popup--close-btn"),r=this.el.querySelector(".nme-register-popup--non-submit-btn"),n=document.querySelector(".nme-register-popup--bg-layer");this.closePopup=this.closePopup.bind(this),o.addEventListener("click",this.closePopup),r.addEventListener("click",this.closePopup),n.addEventListener("click",this.closePopup)}openPopupByTime(){this.openPopup()}openPopupByScroll(){const e=this.el.getAttribute("data-register-popup-scroll");console.log(window.scrollY),window.scrollY>=e&&this.openPopup()}openPopup(){const e=this.el;document.querySelector(".nme-register-popup--bg-layer").classList.add("open"),e.classList.add("open")}closePopup(){this.el.classList.remove("open");document.querySelector(".nme-register-popup--bg-layer").classList.remove("open"),this._popupId&&("sessionStorage"===this._storageType?o(this._popupClosedStorageName):r(this._popupClosedStorageName))}}var d=i(8203);class g extends s.Z{init(){this.sliderInstance=(0,d.W)({container:this.el,items:1,slideBy:"page",nav:!1,controls:!1,loop:!0,mouseDrag:!0,autoplay:!0,autoplayTimeout:2500,autoplayButtonOutput:!1})}}var m=i(6656);class b extends s.Z{init(){this._client=new u.Z,this._updateMessage=this._updateMessage.bind(this),this._fetchRemainingAmount=this._fetchRemainingAmount.bind(this),this.options.hideBarWhenNoItems=!!this.el.getAttribute("data-hide-bar-when-no-items"),this._messageContainerEl=this.el.querySelector(".nme-free-shipping-bar__message"),this._subscribeToCartUpdate(),this._applyStoredContent(),this.options.hideBarWhenNoItems&&this._hideBar(),this._fetchRemainingAmount()}_showBar(){this.el.style.maxHeight=""}_hideBar(){this.el.style.maxHeight=0}_applyStoredContent(){const e=m.Z.getItem(this.options.shippingBarWidgetStorageKey);e&&this._updateMessage(e)}_subscribeToCartUpdate(){const e=PluginManager.getPluginInstances("OffCanvasCart"),t=e&&e[0];t&&t.$emitter.subscribe("fetchCartWidgets",(()=>{this._fetchRemainingAmount()}))}_fetchRemainingAmount(){const e=this.el.getAttribute("data-base-url");this._client.get(`${e}widgets/neno-marekting-essentials/free-shipping-bar/info`,(e=>{m.Z.setItem(this.options.shippingBarWidgetStorageKey,e),this._updateMessage(e)}))}_updateMessage(e){this.el.getAttribute("data-free-shipping-goal")&&"string"==typeof e&&(this._messageContainerEl.innerHTML=e,this._messageContainerEl.children.length?this._showBar():this._hideBar())}}!function(e,t,i){(t=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var i=e[Symbol.toPrimitive];if(void 0!==i){var s=i.call(e,t||"default");if("object"!=typeof s)return s;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i}(b,"options",{shippingBarWidgetStorageKey:"nenoMarketingEssentialsFreeShippingWidget",hideBarWhenNoItems:!1});const y=window.PluginManager;y.register("NewsletterPopup",n,"[data-newsletter-popup]"),y.register("NewsletterPopupForm",h,"[data-newsletter-popup-form]"),y.register("RegisterPopup",c,"[data-register-popup]"),y.register("ConversionBar",g,"[data-conversion-bar]"),y.register("FreeShippingBar",b,"[data-free-shipping-bar]")}},e=>{e.O(0,["vendor-node","vendor-shared"],(()=>{return t=5690,e(e.s=t);var t}));e.O()}]);