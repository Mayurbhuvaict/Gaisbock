(this.webpackJsonpPlugingaisbock=this.webpackJsonpPlugingaisbock||[]).push([[16],{"3Vi/":function(e,t,i){var n=i("6wcd");n.__esModule&&(n=n.default),"string"==typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);(0,i("P8hj").default)("7a62b3ce",n,!0,{})},"6wcd":function(e,t,i){},KHlV:function(e,t,i){"use strict";i.r(t);i("3Vi/");var n=i("0576"),l=Shopware,a=l.Mixin,s=l.Filter;t.default={template:'{% block sw_cms_element_ict_about_title_text_image %}\n    \n    <div class="sw-cms-el-ict-about-title-text-image">\n        {% block sw_cms_element_ict_about_title_text_image_content %}\n            <div class="sw-cms-element-ict-about-title-text-image-content">\n                <h2 v-if="element.config.title">{{ titleText }}</h2>\n                <h2 v-else>Lorem ipsum dolor sit amet</h2>\n                <div class="sw-cms-el-image">\n                    <img :src="titleMediaUrl"  alt="">\n                </div>\n\n                <sw-text-editor\n                        v-model="element.config.content.value"\n                        :disabled="disabled"\n                        :vertical-align="element.config.verticalAlign.value"\n                        :allow-inline-data-mapping="true"\n                        :is-inline-edit="true"\n                        sanitize-input\n                        enable-transparent-background\n                        @blur="onBlur"\n                        @input="onInput"\n                />\n            </div>\n            <div class="sw-cms-element-ict-about-title-text-image-image">\n                <div class="sw-cms-el-ict-about-image" :class="displayModeClass" :style="styles">\n                    {% block sw_cms_element_image_content %}\n                        <img :src="aboutMediaUrl" :style="imgStyles" alt="">\n                    {% endblock %}\n                </div>\n            </div>\n        {% endblock %}\n    </div>\n{% endblock %}',mixins:[a.getByName("cms-element")],computed:{titleText:function(){return this.element.config.title.value},displayModeClass:function(){return"standard"===this.element.config.displayMode.value?null:"is--".concat(this.element.config.displayMode.value)},styles:function(){return{"min-height":"cover"===this.element.config.displayMode.value&&this.element.config.minHeight.value&&0!==this.element.config.minHeight.value?this.element.config.minHeight.value:"340px"}},position:function(){var e,t;return null!==(e=this.element)&&void 0!==e&&null!==(t=e.data)&&void 0!==t&&t.position?this.element.data.position:{position:"left"}},imgStyles:function(){return{"align-self":this.element.config.verticalAlign.value||null}},titleMediaUrl:function(){var e=n.a.MEDIA.previewGlasses.slice(n.a.MEDIA.previewGlasses.lastIndexOf("/")+1),t=this.assetFilter("administration/static/img/cms/".concat(e)),i=this.element.data.titleMedia,l=this.element.config.titleMedia;if("mapped"===l.source){var a=this.getDemoValue(l.value);return null!=a&&a.url?a.url:t}if("default"===l.source){var s=l.value.slice(l.value.lastIndexOf("/")+1);return this.assetFilter("/administration/static/img/cms/".concat(s))}return null!=i&&i.id?this.element.data.titleMedia.url:null!=i&&i.url?this.assetFilter(l.url):t},aboutMediaUrl:function(){var e=n.a.MEDIA.previewMountain.slice(n.a.MEDIA.previewMountain.lastIndexOf("/")+1),t=this.assetFilter("administration/static/img/cms/".concat(e)),i=this.element.data.aboutMedia,l=this.element.config.aboutMedia;if("mapped"===l.source){var a=this.getDemoValue(l.value);return null!=a&&a.url?a.url:t}if("default"===l.source){var s=l.value.slice(l.value.lastIndexOf("/")+1);return this.assetFilter("/administration/static/img/cms/".concat(s))}return null!=i&&i.id?this.element.data.aboutMedia.url:null!=i&&i.url?this.assetFilter(l.url):t},assetFilter:function(){return s.getByName("asset")},titleMediaConfigValue:function(){var e,t,i;return null===(e=this.element)||void 0===e||null===(t=e.config)||void 0===t||null===(i=t.sliderItems)||void 0===i?void 0:i.value},aboutMediaConfigValue:function(){var e,t,i;return null===(e=this.element)||void 0===e||null===(t=e.config)||void 0===t||null===(i=t.sliderItems)||void 0===i?void 0:i.value}},data:function(){return{editable:!0,demoValue:""}},watch:{cmsPageState:{deep:!0,handler:function(){this.$forceUpdate(),this.updateDemoValue()}},"element.config.content.source":{handler:function(){this.updateDemoValue()}},titleMediaConfigValue:function(e){var t,i,n,l,a,s,o=null===(t=this.element)||void 0===t||null===(i=t.data)||void 0===i||null===(n=i.titleMedia)||void 0===n?void 0:n.id;"static"===(null===(l=this.element)||void 0===l||null===(a=l.config)||void 0===a||null===(s=a.titleMedia)||void 0===s?void 0:s.source)&&o&&e!==o&&(this.element.config.titleMedia.value=o)},aboutMediaConfigValue:function(e){var t,i,n,l,a,s,o=null===(t=this.element)||void 0===t||null===(i=t.data)||void 0===i||null===(n=i.aboutMedia)||void 0===n?void 0:n.id;"static"===(null===(l=this.element)||void 0===l||null===(a=l.config)||void 0===a||null===(s=a.aboutMedia)||void 0===s?void 0:s.source)&&o&&e!==o&&(this.element.config.aboutMedia.value=o)}},created:function(){this.createdComponent()},methods:{createdComponent:function(){this.initElementConfig("gaisbock-about-title-text-image"),this.initElementData("gaisbock-about-title-text-image")},updateDemoValue:function(){"mapped"===this.element.config.content.source&&(this.demoValue=this.getDemoValue(this.element.config.content.value))},onBlur:function(e){this.emitChanges(e)},onInput:function(e){this.emitChanges(e)},emitChanges:function(e){e!==this.element.config.content.value&&(this.element.config.content.value=e,this.$emit("element-update",this.element))}}}}}]);