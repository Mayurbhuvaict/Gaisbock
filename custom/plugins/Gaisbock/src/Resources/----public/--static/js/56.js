/*! For license information please see 56.js.LICENSE.txt */
(this.webpackJsonpPlugingaisbock=this.webpackJsonpPlugingaisbock||[]).push([[56],{GGjy:function(e,n,t){"use strict";t.r(n);function i(e){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function o(){o=function(){return e};var e={},n=Object.prototype,t=n.hasOwnProperty,a=Object.defineProperty||function(e,n,t){e[n]=t.value},l="function"==typeof Symbol?Symbol:{},c=l.iterator||"@@iterator",r=l.asyncIterator||"@@asyncIterator",s=l.toStringTag||"@@toStringTag";function m(e,n,t){return Object.defineProperty(e,n,{value:t,enumerable:!0,configurable:!0,writable:!0}),e[n]}try{m({},"")}catch(e){m=function(e,n,t){return e[n]=t}}function u(e,n,t,i){var o=n&&n.prototype instanceof g?n:g,l=Object.create(o.prototype),c=new E(i||[]);return a(l,"_invoke",{value:x(e,t,c)}),l}function d(e,n,t){try{return{type:"normal",arg:e.call(n,t)}}catch(e){return{type:"throw",arg:e}}}e.wrap=u;var f={};function g(){}function p(){}function h(){}var v={};m(v,c,(function(){return this}));var w=Object.getPrototypeOf,b=w&&w(w(I([])));b&&b!==n&&t.call(b,c)&&(v=b);var _=h.prototype=g.prototype=Object.create(v);function y(e){["next","throw","return"].forEach((function(n){m(e,n,(function(e){return this._invoke(n,e)}))}))}function T(e,n){function o(a,l,c,r){var s=d(e[a],e,l);if("throw"!==s.type){var m=s.arg,u=m.value;return u&&"object"==i(u)&&t.call(u,"__await")?n.resolve(u.__await).then((function(e){o("next",e,c,r)}),(function(e){o("throw",e,c,r)})):n.resolve(u).then((function(e){m.value=e,c(m)}),(function(e){return o("throw",e,c,r)}))}r(s.arg)}var l;a(this,"_invoke",{value:function(e,t){function i(){return new n((function(n,i){o(e,t,n,i)}))}return l=l?l.then(i,i):i()}})}function x(e,n,t){var i="suspendedStart";return function(o,a){if("executing"===i)throw new Error("Generator is already running");if("completed"===i){if("throw"===o)throw a;return S()}for(t.method=o,t.arg=a;;){var l=t.delegate;if(l){var c=k(l,t);if(c){if(c===f)continue;return c}}if("next"===t.method)t.sent=t._sent=t.arg;else if("throw"===t.method){if("suspendedStart"===i)throw i="completed",t.arg;t.dispatchException(t.arg)}else"return"===t.method&&t.abrupt("return",t.arg);i="executing";var r=d(e,n,t);if("normal"===r.type){if(i=t.done?"completed":"suspendedYield",r.arg===f)continue;return{value:r.arg,done:t.done}}"throw"===r.type&&(i="completed",t.method="throw",t.arg=r.arg)}}}function k(e,n){var t=n.method,i=e.iterator[t];if(void 0===i)return n.delegate=null,"throw"===t&&e.iterator.return&&(n.method="return",n.arg=void 0,k(e,n),"throw"===n.method)||"return"!==t&&(n.method="throw",n.arg=new TypeError("The iterator does not provide a '"+t+"' method")),f;var o=d(i,e.iterator,n.arg);if("throw"===o.type)return n.method="throw",n.arg=o.arg,n.delegate=null,f;var a=o.arg;return a?a.done?(n[e.resultName]=a.value,n.next=e.nextLoc,"return"!==n.method&&(n.method="next",n.arg=void 0),n.delegate=null,f):a:(n.method="throw",n.arg=new TypeError("iterator result is not an object"),n.delegate=null,f)}function $(e){var n={tryLoc:e[0]};1 in e&&(n.catchLoc=e[1]),2 in e&&(n.finallyLoc=e[2],n.afterLoc=e[3]),this.tryEntries.push(n)}function M(e){var n=e.completion||{};n.type="normal",delete n.arg,e.completion=n}function E(e){this.tryEntries=[{tryLoc:"root"}],e.forEach($,this),this.reset(!0)}function I(e){if(e){var n=e[c];if(n)return n.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var i=-1,o=function n(){for(;++i<e.length;)if(t.call(e,i))return n.value=e[i],n.done=!1,n;return n.value=void 0,n.done=!0,n};return o.next=o}}return{next:S}}function S(){return{value:void 0,done:!0}}return p.prototype=h,a(_,"constructor",{value:h,configurable:!0}),a(h,"constructor",{value:p,configurable:!0}),p.displayName=m(h,s,"GeneratorFunction"),e.isGeneratorFunction=function(e){var n="function"==typeof e&&e.constructor;return!!n&&(n===p||"GeneratorFunction"===(n.displayName||n.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,h):(e.__proto__=h,m(e,s,"GeneratorFunction")),e.prototype=Object.create(_),e},e.awrap=function(e){return{__await:e}},y(T.prototype),m(T.prototype,r,(function(){return this})),e.AsyncIterator=T,e.async=function(n,t,i,o,a){void 0===a&&(a=Promise);var l=new T(u(n,t,i,o),a);return e.isGeneratorFunction(t)?l:l.next().then((function(e){return e.done?e.value:l.next()}))},y(_),m(_,s,"Generator"),m(_,c,(function(){return this})),m(_,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var n=Object(e),t=[];for(var i in n)t.push(i);return t.reverse(),function e(){for(;t.length;){var i=t.pop();if(i in n)return e.value=i,e.done=!1,e}return e.done=!0,e}},e.values=I,E.prototype={constructor:E,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(M),!e)for(var n in this)"t"===n.charAt(0)&&t.call(this,n)&&!isNaN(+n.slice(1))&&(this[n]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var n=this;function i(t,i){return l.type="throw",l.arg=e,n.next=t,i&&(n.method="next",n.arg=void 0),!!i}for(var o=this.tryEntries.length-1;o>=0;--o){var a=this.tryEntries[o],l=a.completion;if("root"===a.tryLoc)return i("end");if(a.tryLoc<=this.prev){var c=t.call(a,"catchLoc"),r=t.call(a,"finallyLoc");if(c&&r){if(this.prev<a.catchLoc)return i(a.catchLoc,!0);if(this.prev<a.finallyLoc)return i(a.finallyLoc)}else if(c){if(this.prev<a.catchLoc)return i(a.catchLoc,!0)}else{if(!r)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return i(a.finallyLoc)}}}},abrupt:function(e,n){for(var i=this.tryEntries.length-1;i>=0;--i){var o=this.tryEntries[i];if(o.tryLoc<=this.prev&&t.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=n&&n<=a.finallyLoc&&(a=null);var l=a?a.completion:{};return l.type=e,l.arg=n,a?(this.method="next",this.next=a.finallyLoc,f):this.complete(l)},complete:function(e,n){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&n&&(this.next=n),f},finish:function(e){for(var n=this.tryEntries.length-1;n>=0;--n){var t=this.tryEntries[n];if(t.finallyLoc===e)return this.complete(t.completion,t.afterLoc),M(t),f}},catch:function(e){for(var n=this.tryEntries.length-1;n>=0;--n){var t=this.tryEntries[n];if(t.tryLoc===e){var i=t.completion;if("throw"===i.type){var o=i.arg;M(t)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,n,t){return this.delegate={iterator:I(e),resultName:n,nextLoc:t},"next"===this.method&&(this.arg=void 0),f}},e}function a(e,n,t,i,o,a,l){try{var c=e[a](l),r=c.value}catch(e){return void t(e)}c.done?n(r):Promise.resolve(r).then(i,o)}function l(e){return function(){var n=this,t=arguments;return new Promise((function(i,o){var l=e.apply(n,t);function c(e){a(l,i,o,c,r,"next",e)}function r(e){a(l,i,o,c,r,"throw",e)}c(void 0)}))}}var c=Shopware.Mixin;n.default={template:'\n{% block sw_cms_el_config_text %}\n    <sw-tabs\n            position-identifier="sw-cms-element-config-text"\n            class="sw-cms-el-config-text__tabs"\n            default-item="content"\n    >\n\n        <template #default="{ active }">\n            \n            {% block sw_cms_el_config_text_tab_content %}\n                <sw-tabs-item\n                        :title="$tc(\'sw-cms.elements.general.config.tab.content\')"\n                        name="content"\n                        :active-tab="active"\n                >\n                    {{ $tc(\'sw-cms.elements.general.config.tab.content\') }}\n                </sw-tabs-item>\n            {% endblock %}\n            \n            {% block sw_cms_el_text_config_tab_options %}\n                <sw-tabs-item\n                        :title="$tc(\'sw-cms.elements.general.config.tab.image\')"\n                        name="images"\n                        :active-tab="active"\n                >\n                    {{ $tc(\'sw-cms.elements.general.config.tab.image\') }}\n                </sw-tabs-item>\n            {% endblock %}\n        </template>\n\n        <template\n                #content="{ active }"\n        >\n            \n            {% block sw_cms_el_text_config_content %}\n                <sw-container\n                        v-if="active === \'content\'"\n                        class="sw-cms-el-config-text__tab-content"\n                >\n                    {% block sw_cms_element_main_title_config %}\n                        <sw-text-field\n                                v-model="element.config.subTitle.value"\n                                :label="$tc(\'sw-cms.elements.image.config.label.subTitle\')"\n                                :placeholder="$tc(\'sw-cms.elements.image.config.placeholder.enterSubTitle\')"\n                                @input="onChangeSubTitle"\n                        />\n\n                        <sw-text-field\n                                v-model="element.config.mainTitle.value"\n                                :label="$tc(\'sw-cms.elements.image.config.label.mainTitle\')"\n                                :placeholder="$tc(\'sw-cms.elements.image.config.placeholder.enterMainTitle\')"\n                                @input="onChangeMainTitle"\n                        />\n                    {% endblock %}\n\n                    <sw-cms-mapping-field\n                            v-model="element.config.content"\n                            :label="$tc(\'sw-cms.elements.text.config.label.content\')"\n                            value-types="string"\n                    >\n                        <sw-text-editor\n                                :value="element.config.content.value"\n                                :allow-inline-data-mapping="true"\n                                sanitize-input\n                                enable-transparent-background\n                                @input="onInput"\n                                @blur="onBlur"\n                        />\n\n                        <template #preview="{ demoValue }">\n                            <div class="sw-cms-el-config-text__mapping-preview">\n                                <div v-html="$sanitize(demoValue)"></div>\n                            </div>\n                        </template>\n                    </sw-cms-mapping-field>\n\n                    {% block sw_cms_el_text_config_settings_vertical_align %}\n                        <sw-select-field\n                                v-model="element.config.verticalAlign.value"\n                                :label="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                                :placeholder="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                        >\n                            <option value="flex-start">\n                                {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignTop\') }}\n                            </option>\n                            <option value="center">\n                                {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignCenter\') }}\n                            </option>\n                            <option value="flex-end">\n                                {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignBottom\') }}\n                            </option>\n                        </sw-select-field>\n                    {% endblock %}\n\n                    {% block sw_cms_element_button_config_text %}\n                        <div class="sw-cms-el-config-button__text">\n                            <sw-field\n                                    v-model="element.config.buttonOneText.value"\n                                    class="sw-cms-el-config-button__text-tab"\n                                    name="buttonText"\n                                    type="text"\n                                    :label="$tc(\'sw-cms.elements.buttonTitle\')"\n                            />\n                        </div>\n                    {% endblock %}\n                    {% block sw_cms_element_button_config_link %}\n                        <div class="sw-cms-el-config-button__link">\n                            <sw-dynamic-url-field\n                                    v-model="element.config.buttonOneUrl.value"\n                            />\n                            <sw-field\n                                    v-model="element.config.buttonOneNewTab.value"\n                                    class="sw-cms-el-config-button__link-tab"\n                                    type="switch"\n                                    :label="$tc(\'sw-cms.elements.image.config.label.linkNewTab\')"\n                            />\n                        </div>\n                    {% endblock %}\n\n                </sw-container>\n            {% endblock %}\n\n            \n            {% block sw_cms_el_text_config_settings %}\n                <sw-container\n                        v-if="active === \'images\'"\n                        class="sw-cms-el-config-image__tab-image"\n                >\n                    \n                    \n                    {% block sw_cms_element_image_config %}\n                        <div class="sw-cms-el-config-image">\n                            \n                            {% block sw_cms_element_image_config_media_upload %}\n                                <sw-cms-mapping-field\n                                        v-model="element.config.media"\n                                        :label="$tc(\'sw-cms.elements.image.label\')"\n                                        value-types="entity"\n                                        entity="media"\n                                >\n                                    <sw-media-upload-v2\n                                            variant="regular"\n                                            :upload-tag="uploadTag"\n                                            :source="previewSource"\n                                            :allow-multi-select="false"\n                                            :default-folder="cmsPageState.pageEntityName"\n                                            :caption="$tc(\'sw-cms.elements.general.config.caption.mediaUpload\')"\n                                            @media-upload-sidebar-open="onOpenMediaModal"\n                                            @media-upload-remove-image="onImageRemove"\n                                    />\n\n                                    <template #preview="{ demoValue }">\n                                        <div class="sw-cms-el-config-image__mapping-preview">\n                                            <img\n                                                    v-if="demoValue.url"\n                                                    :src="demoValue.url"\n                                                    alt=""\n                                            >\n                                            <sw-alert\n                                                    v-else\n                                                    class="sw-cms-el-config-image__preview-info"\n                                                    variant="info"\n                                            >\n                                                {{ $tc(\'sw-cms.detail.label.mappingEmptyPreview\') }}\n                                            </sw-alert>\n                                        </div>\n                                    </template>\n                                </sw-cms-mapping-field>\n\n                                <sw-upload-listener\n                                        :upload-tag="uploadTag"\n                                        auto-upload\n                                        @media-upload-finish="onImageUpload"\n                                />\n\n                                <sw-cms-mapping-field\n                                        v-model="element.config.mediaTwo"\n                                        :label="$tc(\'sw-cms.elements.image.label\')"\n                                        value-types="entity"\n                                        entity="media"\n                                >\n                                    <sw-media-upload-v2\n                                            variant="regular"\n                                            :upload-tag="uploadTagTwo"\n                                            :source="previewSourceTwo"\n                                            :allow-multi-select="false"\n                                            :default-folder="cmsPageState.pageEntityName"\n                                            :caption="$tc(\'sw-cms.elements.general.config.caption.mediaUpload\')"\n                                            @media-upload-sidebar-open="onOpenMediaModalTwo"\n                                            @media-upload-remove-image="onImageRemoveTwo"\n                                    />\n                                    <template #preview="{ demoValue }">\n                                        <div class="sw-cms-el-config-image__mapping-preview">\n                                            <img\n                                                    v-if="demoValue.url"\n                                                    :src="demoValue.url"\n                                                    alt=""\n                                            >\n                                            <sw-alert\n                                                    v-else\n                                                    class="sw-cms-el-config-image__preview-info"\n                                                    variant="info"\n                                            >\n                                                {{ $tc(\'sw-cms.detail.label.mappingEmptyPreview\') }}\n                                            </sw-alert>\n                                        </div>\n                                    </template>\n                                </sw-cms-mapping-field>\n                                <sw-upload-listener\n                                        :upload-tag="uploadTagTwo"\n                                        auto-upload\n                                        @media-upload-finish="onImageUploadTwo"\n                                />\n                            {% endblock %}\n                            <sw-select-field\n                                    v-model="element.config.position.value"\n                                    :label="$tc(\'sw-cms.elements.gaisbockCustomTextImageSlider.config.textFields.TextImagePosition\')"\n                            >\n                                <option value="left" selected>\n                                    {{ $tc(\'sw-cms.elements.general.config.label.left\') }}\n                                </option>\n                                <option value="center">\n                                    {{ $tc(\'sw-cms.elements.general.config.label.center\') }}\n                                </option>\n                                <option value="right">\n                                    {{ $tc(\'sw-cms.elements.general.config.label.right\') }}\n                                </option>\n                            </sw-select-field>\n                            \n                            {% block sw_cms_element_image_config_display_mode %}\n                                <sw-select-field\n                                        v-model="element.config.displayMode.value"\n                                        class="sw-cms-el-config-image__display-mode"\n                                        :label="$tc(\'sw-cms.elements.general.config.label.displayMode\')"\n                                        @change="onChangeDisplayMode"\n                                >\n                                    <option value="standard">\n                                        {{ $tc(\'sw-cms.elements.general.config.label.displayModeStandard\') }}\n                                    </option>\n                                    <option value="cover">\n                                        {{ $tc(\'sw-cms.elements.general.config.label.displayModeCover\') }}\n                                    </option>\n                                    <option value="stretch">\n                                        {{ $tc(\'sw-cms.elements.general.config.label.displayModeStretch\') }}\n                                    </option>\n                                </sw-select-field>\n                            {% endblock %}\n\n                            <template v-if="element.config.displayMode.value === \'cover\'">\n                                \n                                {% block sw_cms_element_image_config_min_height %}\n                                    <sw-text-field\n                                            v-model="element.config.minHeight.value"\n                                            :label="$tc(\'sw-cms.elements.image.config.label.minHeight\')"\n                                            :placeholder="$tc(\'sw-cms.elements.image.config.placeholder.enterMinHeight\')"\n                                            @input="onChangeMinHeight"\n                                    />\n                                {% endblock %}\n                            </template>\n\n                            \n                            {% block sw_cms_element_image_config_vertical_align %}\n                                <sw-select-field\n                                        v-model="element.config.verticalAlign.value"\n                                        :label="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                                        :placeholder="$tc(\'sw-cms.elements.general.config.label.verticalAlign\')"\n                                        :disabled="element.config.displayMode.value === \'cover\'"\n                                >\n                                    <option value="flex-start">\n                                        {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignTop\') }}\n                                    </option>\n                                    <option value="center">\n                                        {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignCenter\') }}\n                                    </option>\n                                    <option value="flex-end">\n                                        {{ $tc(\'sw-cms.elements.general.config.label.verticalAlignBottom\') }}\n                                    </option>\n                                </sw-select-field>\n                            {% endblock %}\n\n                            \n                            {% block sw_cms_element_image_config_link %}\n                                <div class="sw-cms-el-config-image__link">\n                                    <sw-dynamic-url-field\n                                            v-model="element.config.url.value"\n                                    />\n                                    <sw-field\n                                            v-model="element.config.newTab.value"\n                                            class="sw-cms-el-config-image__link-tab"\n                                            type="switch"\n                                            :label="$tc(\'sw-cms.elements.image.config.label.linkNewTab\')"\n                                    />\n                                </div>\n                            {% endblock %}\n\n                            \n                            {% block sw_cms_element_image_config_media_modal %}\n                                <sw-media-modal-v2\n                                        v-if="mediaModalIsOpen"\n                                        variant="regular"\n                                        :caption="$tc(\'sw-cms.elements.general.config.caption.mediaUpload\')"\n                                        :entity-context="cmsPageState.entityName"\n                                        :allow-multi-select="false"\n                                        :initial-folder-id="cmsPageState.defaultMediaFolderId"\n                                        @media-upload-remove-image="onImageRemove"\n                                        @media-modal-selection-change="onSelectionChanges"\n                                        @modal-close="onCloseModal"\n                                />\n                            {% endblock %}\n\n                            {% block sw_cms_element_image_config_media_modal %}\n                                <sw-media-modal-v2\n                                        v-if="mediaModalTwoIsOpen"\n                                        variant="regular"\n                                        :caption="$tc(\'sw-cms.elements.general.config.caption.mediaUpload\')"\n                                        :entity-context="cmsPageState.entityName"\n                                        :allow-multi-select="false"\n                                        :initial-folder-id="cmsPageState.defaultMediaFolderId"\n                                        @media-upload-remove-image="onImageRemoveTwo"\n                                        @media-modal-selection-change="onSelectionChangesTwo"\n                                        @modal-close="onCloseModalTwo"\n                                />\n                            {% endblock %}\n                        </div>\n                    {% endblock %}\n                </sw-container>\n            {% endblock %}\n        </template>\n    </sw-tabs>\n{% endblock %}\n',inject:["repositoryFactory"],mixins:[c.getByName("cms-element")],data:function(){return{mediaModalIsOpen:!1,mediaModalTwoIsOpen:!1,initialFolderId:null}},computed:{mediaRepository:function(){return this.repositoryFactory.create("media")},uploadTag:function(){return"cms-element-media-config-".concat(this.element.id)},uploadTagTwo:function(){return"cms-element-media-two-config-".concat(this.element.id)},previewSource:function(){return this.element.data&&this.element.data.media&&this.element.data.media.id?this.element.data.media:this.element.config.media.value},previewSourceTwo:function(){return this.element.data&&this.element.data.mediaTwo&&this.element.data.mediaTwo.id?this.element.data.mediaTwo:this.element.config.mediaTwo.value}},created:function(){this.createdComponent()},methods:{createdComponent:function(){this.initElementConfig("gaisbock-image-titles-button")},onImageUpload:function(e){var n=this;return l(o().mark((function t(){var i,a;return o().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return i=e.targetId,t.next=3,n.mediaRepository.get(i);case 3:a=t.sent,n.element.config.media.value=a.id,n.element.config.url.value=a.url,n.updateElementData(a),n.$emit("element-update",n.element);case 8:case"end":return t.stop()}}),t)})))()},onImageUploadTwo:function(e){var n=this;return l(o().mark((function t(){var i,a;return o().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return i=e.targetId,t.next=3,n.mediaRepository.get(i);case 3:a=t.sent,n.element.config.mediaTwo.value=a.id,n.element.config.urlTwo.value=a.url,n.updateElementDataTwo(a),n.$emit("element-update",n.element);case 8:case"end":return t.stop()}}),t)})))()},onImageRemove:function(){this.element.config.media.value=null,this.updateElementData(),this.$emit("element-update",this.element)},onImageRemoveTwo:function(){this.element.config.mediaTwo.value=null,this.updateElementDataTwo(),this.$emit("element-update",this.element)},onCloseModal:function(){this.mediaModalIsOpen=!1},onCloseModalTwo:function(){this.mediaModalTwoIsOpen=!1},onSelectionChanges:function(e){var n=e[0];this.element.config.media.value=n.id,this.element.config.media.source="static",this.element.config.url.value=n.url,this.updateElementData(n),this.$emit("element-update",this.element)},onSelectionChangesTwo:function(e){var n=e[0];this.element.config.mediaTwo.value=n.id,this.element.config.mediaTwo.source="static",this.element.config.urlTwo.value=n.url,this.updateElementDataTwo(n),this.$emit("element-update",this.element)},updateElementData:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,n=null===e?null:e.id;this.element.data?(this.$set(this.element.data,"mediaId",n),this.$set(this.element.data,"media",e)):this.$set(this.element,"data",{mediaId:n,media:e})},updateElementDataTwo:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,n=null===e?null:e.id;this.element.data?(this.$set(this.element.data,"mediaTwoId",n),this.$set(this.element.data,"mediaTwo",e)):this.$set(this.element,"data",{mediaId:n,media:e})},onOpenMediaModal:function(){this.mediaModalIsOpen=!0},onOpenMediaModalTwo:function(){this.mediaModalTwoIsOpen=!0},onChangeMinHeight:function(e){this.element.config.minHeight.value=null===e?"":e,this.$emit("element-update",this.element)},onChangeMainTitle:function(e){this.element.config.mainTitle.value=null===e?"":e,this.$emit("element-update",this.element)},onChangeSubTitle:function(e){this.element.config.subTitle.value=null===e?"":e,this.$emit("element-update",this.element)},onChangeDisplayMode:function(e){"cover"===e&&(this.element.config.verticalAlign.value=null),this.$emit("element-update",this.element)},onBlur:function(e){this.emitChanges(e)},onInput:function(e){this.emitChanges(e)},emitChanges:function(e){e!==this.element.config.content.value&&(this.element.config.content.value=e,this.$emit("element-update",this.element))}}}}}]);
//# sourceMappingURL=56.js.map