/*! For license information please see 55.js.LICENSE.txt */
(this.webpackJsonpPlugingaisbock=this.webpackJsonpPlugingaisbock||[]).push([[55],{"5LKm":function(e,t,n){"use strict";n.r(t);function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function o(){o=function(){return e};var e={},t=Object.prototype,n=t.hasOwnProperty,r=Object.defineProperty||function(e,t,n){e[t]=n.value},a="function"==typeof Symbol?Symbol:{},l=a.iterator||"@@iterator",c=a.asyncIterator||"@@asyncIterator",s=a.toStringTag||"@@toStringTag";function u(e,t,n){return Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{u({},"")}catch(e){u=function(e,t,n){return e[t]=n}}function m(e,t,n,i){var o=t&&t.prototype instanceof p?t:p,a=Object.create(o.prototype),l=new M(i||[]);return r(a,"_invoke",{value:k(e,n,l)}),a}function d(e,t,n){try{return{type:"normal",arg:e.call(t,n)}}catch(e){return{type:"throw",arg:e}}}e.wrap=m;var f={};function p(){}function h(){}function g(){}var v={};u(v,l,(function(){return this}));var y=Object.getPrototypeOf,w=y&&y(y(S([])));w&&w!==t&&n.call(w,l)&&(v=w);var b=g.prototype=p.prototype=Object.create(v);function _(e){["next","throw","return"].forEach((function(t){u(e,t,(function(e){return this._invoke(t,e)}))}))}function x(e,t){function o(r,a,l,c){var s=d(e[r],e,a);if("throw"!==s.type){var u=s.arg,m=u.value;return m&&"object"==i(m)&&n.call(m,"__await")?t.resolve(m.__await).then((function(e){o("next",e,l,c)}),(function(e){o("throw",e,l,c)})):t.resolve(m).then((function(e){u.value=e,l(u)}),(function(e){return o("throw",e,l,c)}))}c(s.arg)}var a;r(this,"_invoke",{value:function(e,n){function i(){return new t((function(t,i){o(e,n,t,i)}))}return a=a?a.then(i,i):i()}})}function k(e,t,n){var i="suspendedStart";return function(o,r){if("executing"===i)throw new Error("Generator is already running");if("completed"===i){if("throw"===o)throw r;return O()}for(n.method=o,n.arg=r;;){var a=n.delegate;if(a){var l=E(a,n);if(l){if(l===f)continue;return l}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if("suspendedStart"===i)throw i="completed",n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);i="executing";var c=d(e,t,n);if("normal"===c.type){if(i=n.done?"completed":"suspendedYield",c.arg===f)continue;return{value:c.arg,done:n.done}}"throw"===c.type&&(i="completed",n.method="throw",n.arg=c.arg)}}}function E(e,t){var n=t.method,i=e.iterator[n];if(void 0===i)return t.delegate=null,"throw"===n&&e.iterator.return&&(t.method="return",t.arg=void 0,E(e,t),"throw"===t.method)||"return"!==n&&(t.method="throw",t.arg=new TypeError("The iterator does not provide a '"+n+"' method")),f;var o=d(i,e.iterator,t.arg);if("throw"===o.type)return t.method="throw",t.arg=o.arg,t.delegate=null,f;var r=o.arg;return r?r.done?(t[e.resultName]=r.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=void 0),t.delegate=null,f):r:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,f)}function L(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function $(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function M(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(L,this),this.reset(!0)}function S(e){if(e){var t=e[l];if(t)return t.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var i=-1,o=function t(){for(;++i<e.length;)if(n.call(e,i))return t.value=e[i],t.done=!1,t;return t.value=void 0,t.done=!0,t};return o.next=o}}return{next:O}}function O(){return{value:void 0,done:!0}}return h.prototype=g,r(b,"constructor",{value:g,configurable:!0}),r(g,"constructor",{value:h,configurable:!0}),h.displayName=u(g,s,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===h||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,g):(e.__proto__=g,u(e,s,"GeneratorFunction")),e.prototype=Object.create(b),e},e.awrap=function(e){return{__await:e}},_(x.prototype),u(x.prototype,c,(function(){return this})),e.AsyncIterator=x,e.async=function(t,n,i,o,r){void 0===r&&(r=Promise);var a=new x(m(t,n,i,o),r);return e.isGeneratorFunction(n)?a:a.next().then((function(e){return e.done?e.value:a.next()}))},_(b),u(b,s,"Generator"),u(b,l,(function(){return this})),u(b,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=Object(e),n=[];for(var i in t)n.push(i);return n.reverse(),function e(){for(;n.length;){var i=n.pop();if(i in t)return e.value=i,e.done=!1,e}return e.done=!0,e}},e.values=S,M.prototype={constructor:M,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach($),!e)for(var t in this)"t"===t.charAt(0)&&n.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function i(n,i){return a.type="throw",a.arg=e,t.next=n,i&&(t.method="next",t.arg=void 0),!!i}for(var o=this.tryEntries.length-1;o>=0;--o){var r=this.tryEntries[o],a=r.completion;if("root"===r.tryLoc)return i("end");if(r.tryLoc<=this.prev){var l=n.call(r,"catchLoc"),c=n.call(r,"finallyLoc");if(l&&c){if(this.prev<r.catchLoc)return i(r.catchLoc,!0);if(this.prev<r.finallyLoc)return i(r.finallyLoc)}else if(l){if(this.prev<r.catchLoc)return i(r.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<r.finallyLoc)return i(r.finallyLoc)}}}},abrupt:function(e,t){for(var i=this.tryEntries.length-1;i>=0;--i){var o=this.tryEntries[i];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var r=o;break}}r&&("break"===e||"continue"===e)&&r.tryLoc<=t&&t<=r.finallyLoc&&(r=null);var a=r?r.completion:{};return a.type=e,a.arg=t,r?(this.method="next",this.next=r.finallyLoc,f):this.complete(a)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),f},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.finallyLoc===e)return this.complete(n.completion,n.afterLoc),$(n),f}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.tryLoc===e){var i=n.completion;if("throw"===i.type){var o=i.arg;$(n)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,t,n){return this.delegate={iterator:S(e),resultName:t,nextLoc:n},"next"===this.method&&(this.arg=void 0),f}},e}function r(e,t,n,i,o,r,a){try{var l=e[r](a),c=l.value}catch(e){return void n(e)}l.done?t(c):Promise.resolve(c).then(i,o)}var a=Shopware.Mixin;t.default={template:'\n{% block sw_cms_element_image_config %}\n    <div class="sw-cms-el-config-image">\n        \n        {% block sw_cms_element_image_config_media_upload %}\n            <sw-cms-mapping-field\n                    v-model="element.config.media"\n                    :label="$tc(\'sw-cms.elements.image.label\')"\n                    value-types="entity"\n                    entity="media"\n            >\n                <sw-media-upload-v2\n                        variant="regular"\n                        :upload-tag="uploadTag"\n                        :source="previewSource"\n                        :allow-multi-select="false"\n                        :default-folder="cmsPageState.pageEntityName"\n                        :caption="$tc(\'sw-cms.elements.general.config.caption.mediaUpload\')"\n                        @media-upload-sidebar-open="onOpenMediaModal"\n                        @media-upload-remove-image="onImageRemove"\n                />\n\n                <template #preview="{ demoValue }">\n                    <div class="sw-cms-el-config-image__mapping-preview">\n                        <img\n                                v-if="demoValue.url"\n                                :src="demoValue.url"\n                                alt=""\n                        >\n                        <sw-alert\n                                v-else\n                                class="sw-cms-el-config-image__preview-info"\n                                variant="info"\n                        >\n                            {{ $tc(\'sw-cms.detail.label.mappingEmptyPreview\') }}\n                        </sw-alert>\n                    </div>\n                </template>\n            </sw-cms-mapping-field>\n\n            <sw-upload-listener\n                    :upload-tag="uploadTag"\n                    auto-upload\n                    @media-upload-finish="onImageUpload"\n            />\n        {% endblock %}\n\n        \n        {% block sw_cms_element_image_config_display_mode %}\n            <sw-select-field\n                    v-model="element.config.displayMode.value"\n                    class="sw-cms-el-config-image__display-mode"\n                    :label="$tc(\'sw-cms.elements.general.config.label.displayMode\')"\n                    @change="onChangeDisplayMode"\n            >\n                <option value="standard">\n                    {{ $tc(\'sw-cms.elements.general.config.label.displayModeStandard\') }}\n                </option>\n                <option value="cover">\n                    {{ $tc(\'sw-cms.elements.general.config.label.displayModeCover\') }}\n                </option>\n                <option value="stretch">\n                    {{ $tc(\'sw-cms.elements.general.config.label.displayModeStretch\') }}\n                </option>\n            </sw-select-field>\n        {% endblock %}\n\n        <template v-if="element.config.displayMode.value === \'cover\'">\n            \n            {% block sw_cms_element_image_config_min_height %}\n                <sw-text-field\n                        v-model="element.config.minHeight.value"\n                        :label="$tc(\'sw-cms.elements.image.config.label.minHeight\')"\n                        :placeholder="$tc(\'sw-cms.elements.image.config.placeholder.enterMinHeight\')"\n                        @input="onChangeMinHeight"\n                />\n            {% endblock %}\n        </template>\n\n        \n        {% block sw_cms_element_image_config_vertical_align %}\n            <sw-select-field\n                    v-model="element.config.verticalAlign.value"\n                    :label="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                    :placeholder="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                    :disabled="element.config.displayMode.value === \'cover\'"\n            >\n                <option value="flex-start">\n                    {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignTop\') }}\n                </option>\n                <option value="center">\n                    {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignCenter\') }}\n                </option>\n                <option value="flex-end">\n                    {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignBottom\') }}\n                </option>\n            </sw-select-field>\n        {% endblock %}\n\n        \n        {% block sw_cms_element_image_config_link %}\n            <div class="sw-cms-el-config-image__link">\n                <sw-dynamic-url-field\n                        v-model="element.config.url.value"\n                />\n                <sw-field\n                        v-model="element.config.newTab.value"\n                        class="sw-cms-el-config-image__link-tab"\n                        type="switch"\n                        :label="$tc(\'sw-cms.elements.image.config.label.linkNewTab\')"\n                />\n            </div>\n        {% endblock %}\n\n        \n        {% block sw_cms_element_image_config_media_modal %}\n            <sw-media-modal-v2\n                    v-if="mediaModalIsOpen"\n                    variant="regular"\n                    :caption="$tc(\'sw-cms.elements.general.config.caption.mediaUpload\')"\n                    :entity-context="cmsPageState.entityName"\n                    :allow-multi-select="false"\n                    :initial-folder-id="cmsPageState.defaultMediaFolderId"\n                    @media-upload-remove-image="onImageRemove"\n                    @media-modal-selection-change="onSelectionChanges"\n                    @modal-close="onCloseModal"\n            />\n        {% endblock %}\n        <sw-field\n                v-model="element.config.heading.value"\n                class="sw-cms-el-config-button__link-tab"\n                type="text"\n                :label="$tc(\'sw-cms.elements.image.config.label.linkNewTab\')"\n        />\n    </div>\n{% endblock %}\n',inject:["repositoryFactory"],mixins:[a.getByName("cms-element")],data:function(){return{mediaModalIsOpen:!1,initialFolderId:null}},computed:{mediaRepository:function(){return this.repositoryFactory.create("media")},uploadTag:function(){return"cms-element-media-config-".concat(this.element.id)},previewSource:function(){var e,t,n;return null!==(e=this.element)&&void 0!==e&&null!==(t=e.data)&&void 0!==t&&null!==(n=t.media)&&void 0!==n&&n.id?this.element.data.media:this.element.config.media.value}},created:function(){this.createdComponent()},methods:{createdComponent:function(){this.initElementConfig("gaisbock-listpage-text-image")},onImageUpload:function(e){var t,n=this;return(t=o().mark((function t(){var i,r;return o().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return i=e.targetId,t.next=3,n.mediaRepository.get(i);case 3:r=t.sent,n.element.config.media.value=r.id,n.element.config.media.source="static",n.updateElementData(r),n.$emit("element-update",n.element);case 8:case"end":return t.stop()}}),t)})),function(){var e=this,n=arguments;return new Promise((function(i,o){var a=t.apply(e,n);function l(e){r(a,i,o,l,c,"next",e)}function c(e){r(a,i,o,l,c,"throw",e)}l(void 0)}))})()},onImageRemove:function(){this.element.config.media.value=null,this.updateElementData(),this.$emit("element-update",this.element)},onCloseModal:function(){this.mediaModalIsOpen=!1},onSelectionChanges:function(e){var t=e[0];this.element.config.media.value=t.id,this.element.config.media.source="static",this.updateElementData(t),this.$emit("element-update",this.element)},updateElementData:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,t=null===e?null:e.id;this.element.data?(this.$set(this.element.data,"mediaId",t),this.$set(this.element.data,"media",e)):this.$set(this.element,"data",{mediaId:t,media:e})},onOpenMediaModal:function(){this.mediaModalIsOpen=!0},onChangeMinHeight:function(e){this.element.config.minHeight.value=null===e?"":e,this.$emit("element-update",this.element)},onChangeDisplayMode:function(e){"cover"===e&&(this.element.config.verticalAlign.value=null),this.$emit("element-update",this.element)}}}}}]);
//# sourceMappingURL=55.js.map