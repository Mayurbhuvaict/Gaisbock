/*! For license information please see 16.js.LICENSE.txt */
(this.webpackJsonpPlugingaisbock=this.webpackJsonpPlugingaisbock||[]).push([[16],{LQef:function(e,t,n){},M8Xb:function(e,t,n){var o=n("LQef");o.__esModule&&(o=o.default),"string"==typeof o&&(o=[[e.i,o,""]]),o.locals&&(e.exports=o.locals);(0,n("ydqr").default)("ea9099b0",o,!0,{})},aLA4:function(e,t,n){"use strict";n.r(t);n("M8Xb");function o(e){return(o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function i(){i=function(){return e};var e={},t=Object.prototype,n=t.hasOwnProperty,a=Object.defineProperty||function(e,t,n){e[t]=n.value},l="function"==typeof Symbol?Symbol:{},r=l.iterator||"@@iterator",c=l.asyncIterator||"@@asyncIterator",s=l.toStringTag||"@@toStringTag";function u(e,t,n){return Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{u({},"")}catch(e){u=function(e,t,n){return e[t]=n}}function m(e,t,n,o){var i=t&&t.prototype instanceof f?t:f,l=Object.create(i.prototype),r=new A(o||[]);return a(l,"_invoke",{value:x(e,n,r)}),l}function d(e,t,n){try{return{type:"normal",arg:e.call(t,n)}}catch(e){return{type:"throw",arg:e}}}e.wrap=m;var g={};function f(){}function h(){}function p(){}var v={};u(v,r,(function(){return this}));var b=Object.getPrototypeOf,y=b&&b(b(T([])));y&&y!==t&&n.call(y,r)&&(v=y);var w=p.prototype=f.prototype=Object.create(v);function _(e){["next","throw","return"].forEach((function(t){u(e,t,(function(e){return this._invoke(t,e)}))}))}function M(e,t){function i(a,l,r,c){var s=d(e[a],e,l);if("throw"!==s.type){var u=s.arg,m=u.value;return m&&"object"==o(m)&&n.call(m,"__await")?t.resolve(m.__await).then((function(e){i("next",e,r,c)}),(function(e){i("throw",e,r,c)})):t.resolve(m).then((function(e){u.value=e,r(u)}),(function(e){return i("throw",e,r,c)}))}c(s.arg)}var l;a(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){i(e,n,t,o)}))}return l=l?l.then(o,o):o()}})}function x(e,t,n){var o="suspendedStart";return function(i,a){if("executing"===o)throw new Error("Generator is already running");if("completed"===o){if("throw"===i)throw a;return L()}for(n.method=i,n.arg=a;;){var l=n.delegate;if(l){var r=k(l,n);if(r){if(r===g)continue;return r}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if("suspendedStart"===o)throw o="completed",n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o="executing";var c=d(e,t,n);if("normal"===c.type){if(o=n.done?"completed":"suspendedYield",c.arg===g)continue;return{value:c.arg,done:n.done}}"throw"===c.type&&(o="completed",n.method="throw",n.arg=c.arg)}}}function k(e,t){var n=t.method,o=e.iterator[n];if(void 0===o)return t.delegate=null,"throw"===n&&e.iterator.return&&(t.method="return",t.arg=void 0,k(e,t),"throw"===t.method)||"return"!==n&&(t.method="throw",t.arg=new TypeError("The iterator does not provide a '"+n+"' method")),g;var i=d(o,e.iterator,t.arg);if("throw"===i.type)return t.method="throw",t.arg=i.arg,t.delegate=null,g;var a=i.arg;return a?a.done?(t[e.resultName]=a.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=void 0),t.delegate=null,g):a:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,g)}function I(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function $(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function A(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(I,this),this.reset(!0)}function T(e){if(e){var t=e[r];if(t)return t.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,i=function t(){for(;++o<e.length;)if(n.call(e,o))return t.value=e[o],t.done=!1,t;return t.value=void 0,t.done=!0,t};return i.next=i}}return{next:L}}function L(){return{value:void 0,done:!0}}return h.prototype=p,a(w,"constructor",{value:p,configurable:!0}),a(p,"constructor",{value:h,configurable:!0}),h.displayName=u(p,s,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===h||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,p):(e.__proto__=p,u(e,s,"GeneratorFunction")),e.prototype=Object.create(w),e},e.awrap=function(e){return{__await:e}},_(M.prototype),u(M.prototype,c,(function(){return this})),e.AsyncIterator=M,e.async=function(t,n,o,i,a){void 0===a&&(a=Promise);var l=new M(m(t,n,o,i),a);return e.isGeneratorFunction(n)?l:l.next().then((function(e){return e.done?e.value:l.next()}))},_(w),u(w,s,"Generator"),u(w,r,(function(){return this})),u(w,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=Object(e),n=[];for(var o in t)n.push(o);return n.reverse(),function e(){for(;n.length;){var o=n.pop();if(o in t)return e.value=o,e.done=!1,e}return e.done=!0,e}},e.values=T,A.prototype={constructor:A,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach($),!e)for(var t in this)"t"===t.charAt(0)&&n.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function o(n,o){return l.type="throw",l.arg=e,t.next=n,o&&(t.method="next",t.arg=void 0),!!o}for(var i=this.tryEntries.length-1;i>=0;--i){var a=this.tryEntries[i],l=a.completion;if("root"===a.tryLoc)return o("end");if(a.tryLoc<=this.prev){var r=n.call(a,"catchLoc"),c=n.call(a,"finallyLoc");if(r&&c){if(this.prev<a.catchLoc)return o(a.catchLoc,!0);if(this.prev<a.finallyLoc)return o(a.finallyLoc)}else if(r){if(this.prev<a.catchLoc)return o(a.catchLoc,!0)}else{if(!c)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return o(a.finallyLoc)}}}},abrupt:function(e,t){for(var o=this.tryEntries.length-1;o>=0;--o){var i=this.tryEntries[o];if(i.tryLoc<=this.prev&&n.call(i,"finallyLoc")&&this.prev<i.finallyLoc){var a=i;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var l=a?a.completion:{};return l.type=e,l.arg=t,a?(this.method="next",this.next=a.finallyLoc,g):this.complete(l)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),g},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.finallyLoc===e)return this.complete(n.completion,n.afterLoc),$(n),g}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.tryLoc===e){var o=n.completion;if("throw"===o.type){var i=o.arg;$(n)}return i}}throw new Error("illegal catch attempt")},delegateYield:function(e,t,n){return this.delegate={iterator:T(e),resultName:t,nextLoc:n},"next"===this.method&&(this.arg=void 0),g}},e}function a(e,t,n,o,i,a,l){try{var r=e[a](l),c=r.value}catch(e){return void n(e)}r.done?t(c):Promise.resolve(c).then(o,i)}function l(e){return function(){var t=this,n=arguments;return new Promise((function(o,i){var l=e.apply(t,n);function r(e){a(l,o,i,r,c,"next",e)}function c(e){a(l,o,i,r,c,"throw",e)}r(void 0)}))}}var r=Shopware.Mixin;t.default={template:'{% block sw_cms_element_config_gaisbock_about_title_text_image %}\n    <div class="gaisbock-about-title-text-image">\n        <div class="sw-cms-el-config-about-title-text-image">\n            {% block sw_cms_el_config_gaisbock_about_title_text_image_title%}\n                <sw-text-field\n                        v-model="element.config.title.value"\n                        :label="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.titleLabel\')"\n                        sanitize-input\n                        />\n            {% endblock %}\n\n            {% block sw_cms_el_config_gaisbock_about_title_text_image_titleMedia %}\n                <sw-media-upload-v2\n                        variant="regular"\n                        :label="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.titleImage\')"\n                        :upload-tag="uploadTag"\n                        :source="previewSource"\n                        :allow-multi-select="false"\n                        :default-folder="cmsPageState.pageEntityName"\n                        :caption="$tc(\'sw-cms.elements.titleImage.config.label.uploadMediaCaption\')"\n                        @media-upload-sidebar-open="onOpenMediaModal"\n                        @media-upload-remove-image="onImageRemove"/>\n\n                <sw-upload-listener\n                        :upload-tag="uploadTag"\n                        auto-upload\n                        @media-upload-finish="onImageUpload"/>\n\n                {% block sw_cms_el_config_gaisbock_about_title_text_image_media_modal %}\n                    <sw-media-modal-v2\n                            v-if="mediaModalIsOpen"\n                            variant="regular"\n                            :label="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.titleImage\')"\n                            :caption="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.uploadMediaCaption\')"\n                            :entity-context="cmsPageState.entityName"\n                            :allow-multi-select="false"\n                            :initial-folder-id="cmsPageState.defaultMediaFolderId"\n                            @media-upload-remove-image="onImageRemove"\n                            @media-modal-selection-change="onSelectionChanges"\n                            @modal-close="onCloseModal"/>\n                {% endblock %}\n            {% endblock %}\n\n            {% block sw_cms_el_config_gaisbock_about_title_text_image_description %}\n                <sw-text-editor\n                        v-model="element.config.content.value"\n                        :label="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.descriptionLabel\')"\n                        sanitize-input\n                        @input="onInput"\n                        @blur="onBlur"/>\n            {% endblock %}\n\n            {% block sw_cms_el_config_gaisbock_about_title_text_image_aboutMedia %}\n                <sw-media-upload-v2\n                        variant="regular"\n                        :label="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.aboutImage\')"\n                        :upload-tag="uploadTag"\n                        :source="aboutImagePreviewSource"\n                        :allow-multi-select="false"\n                        :default-folder="cmsPageState.pageEntityName"\n                        :caption="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.uploadMediaCaption\')"\n                        @media-upload-sidebar-open="onOpenAboutMediaModal"\n                        @media-upload-remove-image="onAboutImageRemove"\n                />\n\n                <sw-upload-listener\n                        :upload-tag="uploadTag"\n                        auto-upload\n                        @media-upload-finish="onAboutImageUpload"\n                />\n\n                {% block sw_cms_el_config_gaisbock_about_title_text_image_media_modal %}\n                    <sw-media-modal-v2\n                            v-if="aboutMediaModalIsOpen"\n                            variant="regular"\n                            :label="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.aboutImage\')"\n                            :caption="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.uploadMediaCaption\')"\n                            :entity-context="cmsPageState.entityName"\n                            :allow-multi-select="false"\n                            :initial-folder-id="cmsPageState.defaultMediaFolderId"\n                            @media-upload-remove-image="onAboutImageRemove"\n                            @media-modal-selection-change="onAboutSelectionChanges"\n                            @modal-close="onAboutCloseModal"\n                    />\n                {% endblock %}\n            {% endblock %}\n\n            {% block sw_cms_element_config_image_display_mode %}\n                <sw-select-field\n                        v-model="element.config.displayMode.value"\n                        class="sw-cms-el-config-image__display-mode"\n                        :label="$tc(\'sw-cms.elements.general.config.label.displayMode\')"\n                        @change="onChangeDisplayMode"\n                >\n                    <option value="standard">\n                        {{ $tc(\'sw-cms.elements.general.config.label.displayModeStandard\') }}\n                    </option>\n                    <option value="cover">\n                        {{ $tc(\'sw-cms.elements.general.config.label.displayModeCover\') }}\n                    </option>\n                    <option value="stretch">\n                        {{ $tc(\'sw-cms.elements.general.config.label.displayModeStretch\') }}\n                    </option>\n                </sw-select-field>\n            {% endblock %}\n            {% block sw_cms_element_image_config_image_position_align %}\n                <sw-select-field\n                        v-model="element.config.position.value"\n                        :label="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.imagePosition\')"\n                        :placeholder="$tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.imagePosition\')"\n                        :disabled="element.config.displayMode.value === \'cover\'"\n                >\n                    <option value="left">\n                        {{ $tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.imageLeft\') }}\n                    </option>\n                    <option value="right">\n                        {{ $tc(\'sw-cms.elements.gaisbockAboutTitleTextImage.config.label.imageRight\') }}\n                    </option>\n                </sw-select-field>\n            {% endblock %}\n            <template v-if="element.config.displayMode.value === \'cover\'">\n                {% block sw_cms_element_image_config_min_height %}\n                    <sw-text-field\n                            v-model="element.config.minHeight.value"\n                            :label="$tc(\'sw-cms.elements.image.config.label.minHeight\')"\n                            :placeholder="$tc(\'sw-cms.elements.image.config.placeholder.enterMinHeight\')"\n                            @input="onChangeMinHeight"\n                    />\n                {% endblock %}\n            </template>\n            {% block sw_cms_element_image_config_vertical_align %}\n                <sw-select-field\n                        v-model="element.config.verticalAlign.value"\n                        :label="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                        :placeholder="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                        :disabled="element.config.displayMode.value === \'cover\'"\n                >\n                    <option value="flex-start">\n                        {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignTop\') }}\n                    </option>\n                    <option value="center">\n                        {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignCenter\') }}\n                    </option>\n                    <option value="flex-end">\n                        {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignBottom\') }}\n                    </option>\n                </sw-select-field>\n            {% endblock %}\n        </div>\n    </div>\n\n{% endblock %}',inject:["repositoryFactory"],mixins:[r.getByName("cms-element")],data:function(){return{mediaModalIsOpen:!1,aboutMediaModalIsOpen:!1,initialFolderId:null}},computed:{mediaRepository:function(){return this.repositoryFactory.create("media")},uploadTag:function(){return"cms-element-media-config-".concat(this.element.id)},previewSource:function(){return this.element.data&&this.element.data.titleMedia&&this.element.data.titleMedia.id?this.element.data.titleMedia:this.element.config.titleMedia.value},aboutImagePreviewSource:function(){return this.element.data&&this.element.data.aboutMedia&&this.element.data.aboutMedia.id?this.element.data.aboutMedia:this.element.config.aboutMedia.value}},created:function(){this.createdComponent()},methods:{createdComponent:function(){this.initElementConfig("gaisbock-about-title-text-image")},onChangeMinHeight:function(e){this.element.config.minHeight.value=null===e?"":e,this.$emit("element-update",this.element)},onChangeDisplayMode:function(e){"cover"===e&&(this.element.config.verticalAlign.value=null),this.$emit("element-update",this.element)},onBlur:function(e){this.emitChanges(e)},onInput:function(e){this.emitChanges(e)},emitChanges:function(e){e!==this.element.config.content.value&&(this.element.config.content.value=e,this.$emit("element-update",this.element))},onImageUpload:function(e){var t=this;return l(i().mark((function n(){var o,a;return i().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return o=e.targetId,n.next=3,t.mediaRepository.get(o);case 3:a=n.sent,t.element.config.titleMedia.value=a.id,t.updateElementData(a),t.$emit("element-update",t.element);case 7:case"end":return n.stop()}}),n)})))()},onImageRemove:function(){this.element.config.titleMedia.value=null,this.updateElementData(),this.$emit("element-update",this.element)},onCloseModal:function(){this.mediaModalIsOpen=!1},onSelectionChanges:function(e){var t=e[0];this.element.config.titleMedia.value=t.id,this.updateElementData(t),this.$emit("element-update",this.element)},onOpenMediaModal:function(){this.mediaModalIsOpen=!0},updateElementData:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,t=null===e?null:e.id;this.element.data?(this.$set(this.element.data,"titleMediaId",t),this.$set(this.element.data,"titleMedia",e)):(this.$set(this.element,"data",{mediaId:t}),this.$set(this.element,"data",{media:e}))},onAboutImageUpload:function(e){var t=this;return l(i().mark((function n(){var o,a;return i().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return o=e.targetId,n.next=3,t.mediaRepository.get(o);case 3:a=n.sent,t.element.config.aboutMedia.value=a.id,t.updateAboutImageElementData(a),t.$emit("element-update",t.element);case 7:case"end":return n.stop()}}),n)})))()},onAboutImageRemove:function(){this.element.config.aboutMedia.value=null,this.updateAboutImageElementData(),this.$emit("element-update",this.element)},onAboutCloseModal:function(){this.aboutMediaModalIsOpen=!1},onAboutSelectionChanges:function(e){var t=e[0];this.element.config.aboutMedia.value=t.id,this.updateAboutImageElementData(t),this.$emit("element-update",this.element)},onOpenAboutMediaModal:function(){this.aboutMediaModalIsOpen=!0},updateAboutImageElementData:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,t=null===e?null:e.id;this.element.data?(this.$set(this.element.data,"aboutMediaId",t),this.$set(this.element.data,"aboutMedia",e)):(this.$set(this.element,"data",{mediaId:t}),this.$set(this.element,"data",{media:e}))}}}}}]);
//# sourceMappingURL=16.js.map