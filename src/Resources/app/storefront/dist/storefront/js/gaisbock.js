"use strict";(self.webpackChunk=self.webpackChunk||[]).push([["gaisbock"],{4580:(e,t,s)=>{var i=s(6285);class n extends i.Z{init(){this._gaisbockAddMore()}_gaisbockAddMore(){let e=document.querySelector(".readmore-btn")?document.querySelector(".readmore-btn").getAttribute("data-language-value"):null;var t=document.querySelectorAll(".readmore-btn");null!==t&&t.forEach((t=>{const s=t.getAttribute("data-diffrent");var i=document.getElementById("gaisbock-addmore-section-"+s);t.addEventListener("click",(function(){"de-DE"===e?(t.textContent="Mehr lesen"===t.textContent?"Weniger lesen":"Mehr lesen",i.style.display="block"===i.style.display?"none":"block"):"en-GB"===e?(console.log(t),t.textContent="Read More"===t.textContent?"Read Less":"Read More",i.style.display="block"===i.style.display?"none":"block"):(t.textContent="Lire la suite"===t.textContent?"Lire moins":"Lire la suite",i.style.display="block"===i.style.display?"none":"block")}))}))}}class a extends i.Z{init(){this.navClassChangeHeader=document.getElementById("mainNavigationHover"),this.navClassChangeMainDiv=document.getElementById("mainNavigationHoverMainClass"),this.navigationFlyout=document.getElementById("navigationFlyoutId"),this._gaisbockAddRemoveClass(),this._gaisbockCloseOfcanvasRemoveClass()}_gaisbockAddRemoveClass(){const e=document.querySelector(".gaisbock-header-main"),t=(document.querySelector(".main-navigation-menu"),"is-sticky");window.addEventListener("scroll",(()=>{window.pageYOffset>150?e.classList.add(t):e.classList.remove(t)}))}_gaisbockCloseOfcanvasRemoveClass(){const e=document.querySelector(".gaisbock-close"),t=document.querySelector(".hovered");document.addEventListener("DOMContentLoaded",(function(){e.addEventListener("click",(function(){t.classList.remove("hovered")}))}))}}class o extends i.Z{init(){this._gaisbockThemeScroll()}_gaisbockThemeScroll(){document.querySelector("#gaisbock-down-arrow").addEventListener("click",(function(e){e.preventDefault();var t=window.pageYOffset,s=!0;var i=Math.abs(500-t)/10,n=performance.now();i>0&&(s=!0,requestAnimationFrame((function e(a){if(s){var o=(a-n)/i,r=t+(500-t)*o;o<1?(window.scrollTo(0,r),requestAnimationFrame(e)):(window.scrollTo(0,500),s=!1)}})))}))}}class r extends i.Z{init(){var e=document.getElementById("gaisbock-slider");if(null!==e){e.setAttribute("data-base-slider-options",JSON.stringify({slider:{navPosition:"bottom",speed:300,autoplayTimeout:5e3,autoplay:!1,autoplayButtonOutput:!1,nav:!0,controls:!0,autoHeight:!0}}))}}}class l extends i.Z{init(){const e=document.querySelector(".header-main");document.querySelector(".gaisbock-search-button-for-css").addEventListener("click",(()=>{!1===e.classList.contains("hovered-header")?e.classList.add("hovered-header"):e.classList.remove("hovered-header")}));document.querySelector(".gaisbock-search-button-for-css-close").addEventListener("click",(()=>{e.classList.remove("hovered-header")}));const t=document.querySelector("div[domain]");if(t){"frontend.search.page"===t.getAttribute("domain")?e.classList.add("hovered-header"):e.classList.remove("hovered-header")}}}var c=s(1787);class d extends c.Z{init(){super.init()}_openFlyout(e,t){if(!this._isOpen(t)){this._closeAllFlyouts(),e.classList.add(this.options.activeCls),t.classList.add(this.options.activeCls),document.getElementsByClassName("gaisbock-header-main")[0].classList.add("hovered")}this.$emitter.publish("openFlyout")}_closeFlyout(e,t){if(this._isOpen(t)){e.classList.remove(this.options.activeCls),t.classList.remove(this.options.activeCls),document.getElementsByClassName("gaisbock-header-main")[0].classList.remove("hovered")}this.$emitter.publish("closeFlyout")}}class h extends i.Z{init(){document.getElementById("gaisbock-submit").addEventListener("click",(function(){location.reload()}))}}var u=s(3175),v=s(3637),f=(s(7906),s(7474));class g extends u.Z{init(){super.init()}_openMenu(e){const t=f.Z.isXS();u.Z._stopEvent(e),v.Z.open(this._content,this._registerEvents.bind(this),this.options.position,void 0,void 0,t),v.Z.setAdditionalClassName(this.options.additionalOffcanvasClass),document.getElementsByClassName("gaisbock-header-main")[0].classList.add("hovered"),this.$emitter.publish("openMenu")}}class m extends i.Z{init(){this._reveal()}_reveal(){var e=document.querySelectorAll(".reveal");window.addEventListener("scroll",(function(){for(var t=0;t<e.length;t++){var s=window.innerHeight;e[t].getBoundingClientRect().top<s-150?e[t].classList.add("active"):e[t].classList.remove("active")}}))}}class p extends i.Z{init(){this._gaisbockAddMoreWithSlider()}_gaisbockAddMoreWithSlider(){let e=document.querySelector("#readMoreSlider")?document.querySelector("#readMoreSlider").getAttribute("data-language-value"):null;var t=document.querySelectorAll(".readmore-btn-slider");console.log(t),null!==t&&t.forEach((t=>{const s=t.getAttribute("data-diffrent");var i=document.getElementById("gaisbock-custom-text-image-slider-"+s);t.addEventListener("click",(function(){"de-DE"===e?(t.textContent="Mehr lesen"===t.textContent?"Weniger lesen":"Mehr lesen",i.style.display="block"===i.style.display?"none":"block"):"en-GB"===e?(t.textContent="Read More"===t.textContent?"Read Less":"Read More",i.style.display="block"===i.style.display?"none":"block"):(t.textContent="Lire la suite"===t.textContent?"Lire moins":"Lire la suite",i.style.display="block"===i.style.display?"none":"block")}))}))}}const y=window.PluginManager;y.register("gaisbockAddmoreSection",n,"[data-gaisbock-addmore-section]"),y.register("gaisbockAddRemoveClass",a,"[data-gaisbock-add-remove-class]"),y.register("gaisbockThemeScrollSection",o,"[data-gaisbock-scroll-section]"),y.register("gaisbockimageslider",r,"[data-gaisbock-image-slider]"),y.register("gaisbcksearchbar",l,"[data-gaisbock-searchbar]"),y.override("FlyoutMenu",d,"[data-flyout-menu]"),y.register("gaisbockRefreshPage",h,"[data-refresh-page]"),y.override("OffcanvasMenu",g,"[data-offcanvas-menu]"),y.register("gaisbockAnimaion",m,"[data-gaisbock-animation]"),y.register("gaisbockImageWithAddmoreSlider",p,"[data-gaisbock-image-with-addmore-slider]")},1787:(e,t,s)=>{s.d(t,{Z:()=>d});var i,n,a,o=s(6285),r=s(9658),l=s(3206),c=s(1966);class d extends o.Z{init(){this._debouncer=null,this._triggerEls=this.el.querySelectorAll(`[${this.options.triggerDataAttribute}]`),this._closeEls=this.el.querySelectorAll(this.options.closeSelector),this._flyoutEls=this.el.querySelectorAll(`[${this.options.flyoutIdDataAttribute}]`),this._registerEvents()}_registerEvents(){const e=r.Z.isTouchDevice()?"touchstart":"click",t=r.Z.isTouchDevice()?"touchstart":"mouseenter",s=r.Z.isTouchDevice()?"touchstart":"mouseleave";c.Z.iterate(this._triggerEls,(e=>{const i=l.Z.getDataAttribute(e,this.options.triggerDataAttribute);e.addEventListener(t,this._openFlyoutById.bind(this,i,e)),e.addEventListener(s,(()=>this._debounce(this._closeAllFlyouts)))})),c.Z.iterate(this._closeEls,(t=>{t.addEventListener(e,this._closeAllFlyouts.bind(this))})),r.Z.isTouchDevice()||c.Z.iterate(this._flyoutEls,(e=>{e.addEventListener("mousemove",(()=>this._clearDebounce())),e.addEventListener("mouseleave",(()=>this._debounce(this._closeAllFlyouts)))}))}_openFlyout(e,t){this._isOpen(t)||(this._closeAllFlyouts(),e.classList.add(this.options.activeCls),t.classList.add(this.options.activeCls)),this.$emitter.publish("openFlyout")}_closeFlyout(e,t){this._isOpen(t)&&(e.classList.remove(this.options.activeCls),t.classList.remove(this.options.activeCls)),this.$emitter.publish("closeFlyout")}_openFlyoutById(e,t,s){const i=this.el.querySelector(`[${this.options.flyoutIdDataAttribute}='${e}']`);i&&this._debounce(this._openFlyout,i,t),this._isOpen(t)||d._stopEvent(s),this.$emitter.publish("openFlyoutById")}_closeAllFlyouts(){const e=this.el.querySelectorAll(`[${this.options.flyoutIdDataAttribute}]`);c.Z.iterate(e,(e=>{const t=this._retrieveTriggerEl(e);this._closeFlyout(e,t)})),this.$emitter.publish("closeAllFlyouts")}_retrieveTriggerEl(e){const t=l.Z.getDataAttribute(e,this.options.flyoutIdDataAttribute,!1);return this.el.querySelector(`[${this.options.triggerDataAttribute}='${t}']`)}_isOpen(e){return e.classList.contains(this.options.activeCls)}_debounce(e,...t){this._clearDebounce(),this._debouncer=setTimeout(e.bind(this,...t),this.options.debounceTime)}_clearDebounce(){clearTimeout(this._debouncer)}static _stopEvent(e){e&&e.cancelable&&(e.preventDefault(),e.stopImmediatePropagation())}}i=d,a={debounceTime:125,activeCls:"is-open",closeSelector:".js-close-flyout-menu",flyoutIdDataAttribute:"data-flyout-menu-id",triggerDataAttribute:"data-flyout-menu-trigger"},(n=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var s=e[Symbol.toPrimitive];if(void 0!==s){var i=s.call(e,t||"default");if("object"!=typeof i)return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(n="options"))in i?Object.defineProperty(i,n,{value:a,enumerable:!0,configurable:!0,writable:!0}):i[n]=a},3175:(e,t,s)=>{s.d(t,{Z:()=>v});var i,n,a,o=s(6285),r=s(3637),l=s(7906),c=s(8254),d=s(3206),h=s(1966),u=s(7474);class v extends o.Z{init(){this._cache={},this._client=new c.Z,this._content=l.Z.getTemplate(),this._registerEvents()}_registerEvents(){if(this.el.removeEventListener(this.options.tiggerEvent,this._getLinkEventHandler.bind(this)),this.el.addEventListener(this.options.tiggerEvent,this._getLinkEventHandler.bind(this)),r.Z.exists()){const e=r.Z.getOffCanvas();h.Z.iterate(e,(e=>{const t=e.querySelectorAll(this.options.linkSelector);h.Z.iterate(t,(e=>{v._resetLoader(e),e.addEventListener("click",(t=>{this._getLinkEventHandler(t,e)}))}))}))}}_openMenu(e){const t=u.Z.isXS();v._stopEvent(e),r.Z.open(this._content,this._registerEvents.bind(this),this.options.position,void 0,void 0,t),r.Z.setAdditionalClassName(this.options.additionalOffcanvasClass),this.$emitter.publish("openMenu")}_getLinkEventHandler(e,t){if(!t){const t=d.Z.querySelector(document,this.options.initialContentSelector);return this._content=t.innerHTML,t.classList.contains("is-root")?this._cache[this.options.navigationUrl]=this._content:this._fetchMenu(this.options.navigationUrl),this._openMenu(e)}if(v._stopEvent(e),t.classList.contains(this.options.linkLoadingClass))return;v._setLoader(t);const s=d.Z.getAttribute(t,"data-href",!1)||d.Z.getAttribute(t,"href",!1);if(!s)return;let i=this.options.forwardAnimationType;(t.classList.contains(this.options.homeBtnClass)||t.classList.contains(this.options.backBtnClass))&&(i=this.options.backwardAnimationType),this.$emitter.publish("getLinkEventHandler"),this._fetchMenu(s,this._updateOverlay.bind(this,i))}static _setLoader(e){e.classList.add(this.options.linkLoadingClass);const t=e.querySelector(this.options.loadingIconSelector);t&&(t._linkIcon=t.innerHTML,t.innerHTML=l.Z.getTemplate())}static _resetLoader(e){e.classList.remove(this.options.linkLoadingClass);const t=e.querySelector(this.options.loadingIconSelector);t&&t._linkIcon&&(t.innerHTML=t._linkIcon)}_updateOverlay(e,t){if(this._content=t,r.Z.exists()){const s=v._getOffcanvasMenu();s||this._replaceOffcanvasContent(t),this._createOverlayElements();const i=v._getOverlayContent(s),n=v._getMenuContentFromResponse(t);this._replaceOffcanvasMenuContent(e,n,i),this._registerEvents()}this.$emitter.publish("updateOverlay")}_replaceOffcanvasMenuContent(e,t,s){e!==this.options.forwardAnimationType?e!==this.options.backwardAnimationType?(this._animateInstant(t,s),this.$emitter.publish("replaceOffcanvasMenuContent")):this._animateBackward(t,s):this._animateForward(t,s)}_animateInstant(e){this._overlay.innerHTML=e,this.$emitter.publish("animateInstant")}_animateForward(e,t){""===this._placeholder.innerHTML&&(this._placeholder.innerHTML=t),this._overlay.classList.remove(this.options.transitionClass),this._overlay.style.left="100%",this._overlay.innerHTML=e,setTimeout((()=>{this._overlay.classList.add(this.options.transitionClass),this._overlay.style.left="0%"}),1),this.$emitter.publish("animateForward")}_animateBackward(e,t){""===this._overlay.innerHTML&&(this._overlay.innerHTML=t),this._placeholder.innerHTML=e,this._overlay.classList.remove(this.options.transitionClass),this._overlay.style.left="0%",setTimeout((()=>{this._overlay.classList.add(this.options.transitionClass),this._overlay.style.left="100%"}),1),this.$emitter.publish("animateBackward")}static _getMenuContentFromResponse(e){const t=(new DOMParser).parseFromString(e,"text/html");return v._getOverlayContent(t)}static _getOverlayContent(e){if(!e)return"";const t=e.querySelector(this.options.overlayContentSelector);return t?t.innerHTML:""}_createOverlayElements(){const e=v._getOffcanvasMenu();e&&(this._placeholder=v._createPlaceholder(e),this._overlay=v._createNavigationOverlay(e)),this.$emitter.publish("createOverlayElements")}static _createNavigationOverlay(e){const t=v._getOffcanvas(),s=t.querySelector(this.options.overlayClass);if(s)return s;const i=document.createElement("div");return i.classList.add(this.options.overlayClass.substr(1)),i.style.minHeight=`${t.clientHeight}px`,e.appendChild(i),i}static _createPlaceholder(e){const t=v._getOffcanvas(),s=t.querySelector(this.options.placeholderClass);if(s)return s;const i=document.createElement("div");return i.classList.add(this.options.placeholderClass.substr(1)),i.style.minHeight=`${t.clientHeight}px`,e.appendChild(i),i}_fetchMenu(e,t){return!!e&&(this._cache[e]&&"function"==typeof t?t(this._cache[e]):(this.$emitter.publish("beforeFetchMenu"),void this._client.get(e,(s=>{this._cache[e]=s,"function"==typeof t&&t(s)}))))}_replaceOffcanvasContent(e){this._content=e,r.Z.setContent(this._content),this._registerEvents(),this.$emitter.publish("replaceOffcanvasContent")}static _stopEvent(e){e.preventDefault(),e.stopImmediatePropagation()}static _getOffcanvas(){return r.Z.getOffCanvas()[0]}static _getOffcanvasMenu(){return v._getOffcanvas().querySelector(this.options.menuSelector)}}i=v,n="options",a={navigationUrl:window.router["frontend.menu.offcanvas"],position:"left",tiggerEvent:"click",additionalOffcanvasClass:"navigation-offcanvas",linkSelector:".js-navigation-offcanvas-link",loadingIconSelector:".js-navigation-offcanvas-loading-icon",linkLoadingClass:"is-loading",menuSelector:".js-navigation-offcanvas",overlayContentSelector:".js-navigation-offcanvas-overlay-content",initialContentSelector:".js-navigation-offcanvas-initial-content",homeBtnClass:"is-home-link",backBtnClass:"is-back-link",transitionClass:"has-transition",overlayClass:".navigation-offcanvas-overlay",placeholderClass:".navigation-offcanvas-placeholder",forwardAnimationType:"forwards",backwardAnimationType:"backwards"},(n=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var s=e[Symbol.toPrimitive];if(void 0!==s){var i=s.call(e,t||"default");if("object"!=typeof i)return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(n))in i?Object.defineProperty(i,n,{value:a,enumerable:!0,configurable:!0,writable:!0}):i[n]=a},3637:(e,t,s)=>{s.d(t,{Z:()=>d,r:()=>c});var i=s(9658),n=s(2005),a=s(1966);const o="offcanvas",r=350;class l{constructor(){this.$emitter=new n.Z}open(e,t,s,i,n,a,o){this._removeExistingOffCanvas();const r=this._createOffCanvas(s,a,o,i);this.setContent(e,i,n),this._openOffcanvas(r,t)}setContent(e,t,s){const i=this.getOffCanvas();i[0]&&(i[0].innerHTML=e,this._registerEvents(s))}setAdditionalClassName(e){this.getOffCanvas()[0].classList.add(e)}getOffCanvas(){return document.querySelectorAll(`.${o}`)}close(e){const t=this.getOffCanvas();a.Z.iterate(t,(e=>{bootstrap.Offcanvas.getInstance(e).hide()})),setTimeout((()=>{this.$emitter.publish("onCloseOffcanvas",{offCanvasContent:t})}),e)}goBackInHistory(){window.history.back()}exists(){return this.getOffCanvas().length>0}_openOffcanvas(e,t){l.bsOffcanvas.show(),window.history.pushState("offcanvas-open",""),"function"==typeof t&&t()}_registerEvents(e){const t=i.Z.isTouchDevice()?"touchend":"click",s=this.getOffCanvas();a.Z.iterate(s,(t=>{const i=()=>{setTimeout((()=>{t.remove(),this.$emitter.publish("onCloseOffcanvas",{offCanvasContent:s})}),e),t.removeEventListener("hide.bs.offcanvas",i)};t.addEventListener("hide.bs.offcanvas",i)})),window.addEventListener("popstate",this.close.bind(this,e),{once:!0});const n=document.querySelectorAll(".js-offcanvas-close");a.Z.iterate(n,(s=>s.addEventListener(t,this.close.bind(this,e))))}_removeExistingOffCanvas(){l.bsOffcanvas=null;const e=this.getOffCanvas();return a.Z.iterate(e,(e=>e.remove()))}_getPositionClass(e){return"left"===e?"offcanvas-start":"right"===e?"offcanvas-end":`offcanvas-${e}`}_createOffCanvas(e,t,s,i){const n=document.createElement("div");if(n.classList.add(o),n.classList.add(this._getPositionClass(e)),!0===t&&n.classList.add("is-fullwidth"),s){const e=typeof s;if("string"===e)n.classList.add(s);else{if(!Array.isArray(s))throw new Error(`The type "${e}" is not supported. Please pass an array or a string.`);s.forEach((e=>{n.classList.add(e)}))}}return document.body.appendChild(n),l.bsOffcanvas=new bootstrap.Offcanvas(n,{backdrop:!1!==i||"static"}),n}}const c=Object.freeze(new l);class d{static open(e,t=null,s="left",i=!0,n=350,a=!1,o=""){c.open(e,t,s,i,n,a,o)}static setContent(e,t=!0,s=350){c.setContent(e,t,s)}static setAdditionalClassName(e){c.setAdditionalClassName(e)}static close(e=350){c.close(e)}static exists(){return c.exists()}static getOffCanvas(){return c.getOffCanvas()}static REMOVE_OFF_CANVAS_DELAY(){return r}}}},e=>{e.O(0,["vendor-node","vendor-shared"],(()=>{return t=4580,e(e.s=t);var t}));e.O()}]);