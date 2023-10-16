"use strict";(self.webpackChunk=self.webpackChunk||[]).push([["pluszwei-faq-manager"],{5854:(e,t,i)=>{var r,s,n,o=i(6285),l=i(8254),a=i(3206),c=i(46),u=i(1110),h=i(1966),d=i(9658);class v extends o.Z{init(){try{this._inputField=a.Z.querySelector(this.el,"input[type=search]"),this._submitButton=a.Z.querySelector(this.el,"button[type=submit]"),this._url=a.Z.getAttribute(this.el,"data-url")}catch(e){return}this._client=new l.Z,this._registerEvents()}_registerEvents(){this._inputField.addEventListener("input",c.Z.debounce(this._handleInputEvent.bind(this),this.options.searchWidgetDelay),{capture:!0,passive:!0}),this.el.addEventListener("submit",this._handleSearchEvent.bind(this));const e=d.Z.isTouchDevice()?"touchstart":"click";document.body.addEventListener(e,this._onBodyClick.bind(this))}_handleSearchEvent(e){this._inputField.value.trim().length<this.options.searchMinChars&&(e.preventDefault(),e.stopPropagation())}_handleInputEvent(){const e=this._inputField.value.trim();e.length<this.options.searchMinChars?this._clearSuggestResults():this._suggest(e)}_suggest(e){const t=this._url+encodeURIComponent(e),i=new u.Z(this._submitButton);i.create(),this._client.abort(),this._client.get(t,(e=>{this._clearSuggestResults(),i.remove(),this.el.insertAdjacentHTML("beforeend",e)}))}_clearSuggestResults(){const e=document.querySelectorAll(this.options.searchResultSelector);h.Z.iterate(e,(e=>e.remove()))}_onBodyClick(e){e.target.closest(this.options.searchResultSelector)||this._clearSuggestResults()}}r=v,n={searchResultSelector:".js-search-result",searchDelay:250,searchMinChars:3},(s=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var i=e[Symbol.toPrimitive];if(void 0!==i){var r=i.call(e,t||"default");if("object"!=typeof r)return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(s="options"))in r?Object.defineProperty(r,s,{value:n,enumerable:!0,configurable:!0,writable:!0}):r[s]=n;class p extends o.Z{init(){try{this._hideSelectors=a.Z.querySelectorAll(this.el,this.options.hideSelector),this._seeMoreSelector=a.Z.querySelector(this.el,this.options.seeMoreSelector)}catch(e){return}this._registerEvents()}_registerEvents(){this._seeMoreSelector.addEventListener("click",this._onClickSeeMore.bind(this))}_onClickSeeMore(e){this._seeMoreSelector.remove(),h.Z.iterate(this._hideSelectors,(e=>e.classList.remove("hide")))}}!function(e,t,i){(t=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var i=e[Symbol.toPrimitive];if(void 0!==i){var r=i.call(e,t||"default");if("object"!=typeof r)return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i}(p,"options",{hideSelector:".hide",seeMoreSelector:".seeMore"});const g=window.PluginManager;g.register("SearchArticle",v,"[data-search-article]"),g.register("FaqSection",p,"[data-faq-section]")}},e=>{e.O(0,["vendor-node","vendor-shared"],(()=>{return t=5854,e(e.s=t);var t}));e.O()}]);