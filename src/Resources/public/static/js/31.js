(this.webpackJsonpPlugingaisbock=this.webpackJsonpPlugingaisbock||[]).push([[31],{"8cK6":function(e,t,n){},d5jd:function(e,t,n){var i=n("8cK6");i.__esModule&&(i=i.default),"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);(0,n("ydqr").default)("0b99864f",i,!0,{})},"paI/":function(e,t,n){"use strict";n.r(t);var i=n("78S8"),l=(n("d5jd"),Shopware),a=l.Mixin,s=l.Filter;t.default={template:'{% block sw_cms_element_image %}\n    <div class="gaisbock-title-text-image-position">\n        <div class="gaisbock-title-text-image-position-el--inner">\n            <div class="img-text" :class="`${element.config.position.value}`">\n                <div class="sw-cms-el-image" :class="displayModeClass" :style="styles">\n                    {% block sw_cms_element_image_content %}\n                        <img :src="mediaUrl" :style="imgStyles" alt="">\n                    {% endblock %}\n                    {% block sw_cms_element_text %}\n                        <div class="sw-cms-el-title-text">\n                            <div v-if="element.config.content.source === \'mapped\'" class="sw-cms-el-text__mapping-preview content-editor" v-html="$sanitize(demoValue)"></div>\n                            <sw-text-editor\n                                    v-else\n                                    v-model="element.config.content.value"\n                                    :disabled="disabled"\n                                    :vertical-align="element.config.verticalAlign.value"\n                                    :allow-inline-data-mapping="true"\n                                    :is-inline-edit="true"\n                                    sanitize-input\n                                    enable-transparent-background\n                                    @blur="onBlur"\n                                    @input="onInput"\n                            />\n                        </div>\n                    {% endblock %}\n                </div>\n            </div>\n        </div>\n    </div>\n{% endblock %}\n',mixins:[a.getByName("cms-element")],data:function(){return{editable:!0,demoValue:""}},computed:{displayModeClass:function(){return"standard"===this.element.config.displayMode.value?null:"is--".concat(this.element.config.displayMode.value)},styles:function(){return{"min-height":"cover"===this.element.config.displayMode.value&&this.element.config.minHeight.value&&0!==this.element.config.minHeight.value?this.element.config.minHeight.value:"340px"}},position:function(){var e,t;return null!==(e=this.element)&&void 0!==e&&null!==(t=e.data)&&void 0!==t&&t.position?this.element.data.position:{position:"left"}},imgStyles:function(){return{"align-self":this.element.config.verticalAlign.value||null}},mediaUrl:function(){var e=i.a.MEDIA.previewMountain.slice(i.a.MEDIA.previewMountain.lastIndexOf("/")+1),t=this.assetFilter("administration/static/img/cms/".concat(e)),n=this.element.data.media,l=this.element.config.media;if("mapped"===l.source){var a=this.getDemoValue(l.value);return null!=a&&a.url?a.url:t}if("default"===l.source){var s=l.value.slice(l.value.lastIndexOf("/")+1);return this.assetFilter("/administration/static/img/cms/".concat(s))}return null!=n&&n.id?this.element.data.media.url:null!=n&&n.url?this.assetFilter(l.url):t},assetFilter:function(){return s.getByName("asset")},mediaConfigValue:function(){var e,t,n;return null===(e=this.element)||void 0===e||null===(t=e.config)||void 0===t||null===(n=t.sliderItems)||void 0===n?void 0:n.value}},watch:{cmsPageState:{deep:!0,handler:function(){this.$forceUpdate(),this.updateDemoValue()}},"element.config.content.source":{handler:function(){this.updateDemoValue()}},mediaConfigValue:function(e){var t,n,i,l,a,s,o=null===(t=this.element)||void 0===t||null===(n=t.data)||void 0===n||null===(i=n.media)||void 0===i?void 0:i.id;"static"===(null===(l=this.element)||void 0===l||null===(a=l.config)||void 0===a||null===(s=a.media)||void 0===s?void 0:s.source)&&o&&e!==o&&(this.element.config.media.value=o)}},created:function(){this.createdComponent()},methods:{createdComponent:function(){this.initElementConfig("gaisbock-title-text-image"),this.initElementData("gaisbock-title-text-image")},updateDemoValue:function(){"mapped"===this.element.config.content.source&&(this.demoValue=this.getDemoValue(this.element.config.content.value))},onBlur:function(e){this.emitChanges(e)},onInput:function(e){this.emitChanges(e)},emitChanges:function(e){e!==this.element.config.content.value&&(this.element.config.content.value=e,this.$emit("element-update",this.element))}}}}}]);