(this.webpackJsonpPlugingaisbock=this.webpackJsonpPlugingaisbock||[]).push([[25],{BazD:function(e,n,t){"use strict";t.r(n);var i=t("0576"),l=(t("U6lq"),Shopware),a=l.Mixin,s=l.Filter;n.default={template:'{% block sw_cms_element_image %}\n    <div class="gaisbock-image-titles-button-position">\n        <div class="gaisbock-image-titles-button-position-el--inner">\n            <div class="img-text" :class="`${element.config.position.value}`">\n                <div class="sw-cms-el-image" :class="displayModeClass" :style="styles">\n\n                    {% block sw_cms_element_image_content %}\n                        <img :src="mediaUrl" :style="imgStyles" alt="">\n                    {% endblock %}\n\n                    {% block sw_cms_element_image_two_content %}\n                        <img :src="mediaUrl" alt="">\n                    {% endblock %}\n\n                    {% block sw_cms_main_title__content %}\n                        {{ element.config.subTitle.value }}\n                        {{ element.config.mainTitle.value }}\n                    {% endblock %}\n                    {% block sw_cms_element_text %}\n                        <div class="sw-cms-el-text">\n                            <div v-if="element.config.content.source === \'mapped\'" class="sw-cms-el-text__mapping-preview content-editor" v-html="$sanitize(demoValue)"></div>\n                            <sw-text-editor\n                                    v-else\n                                    v-model="element.config.content.value"\n                                    :disabled="disabled"\n                                    :vertical-align="element.config.verticalAlign.value"\n                                    :allow-inline-data-mapping="true"\n                                    :is-inline-edit="true"\n                                    sanitize-input\n                                    enable-transparent-background\n                                    @blur="onBlur"\n                                    @input="onInput"\n                            />\n                        </div>\n                    {% endblock %}\n                    <button class="btn btn-primary"><a\n                                :href="element.config.buttonOneUrl.value">{{ element.config.buttonOneText.value }}</a>\n                    </button>\n                </div>\n            </div>\n        </div>\n    </div>\n{% endblock %}\n',mixins:[a.getByName("cms-element")],data:function(){return{editable:!0,demoValue:""}},computed:{displayModeClass:function(){return"standard"===this.element.config.displayMode.value?null:"is--".concat(this.element.config.displayMode.value)},styles:function(){return{"min-height":"cover"===this.element.config.displayMode.value&&this.element.config.minHeight.value&&0!==this.element.config.minHeight.value?this.element.config.minHeight.value:"340px"}},position:function(){var e,n;return null!==(e=this.element)&&void 0!==e&&null!==(n=e.data)&&void 0!==n&&n.position?this.element.data.position:{position:"left"}},imgStyles:function(){return{"align-self":this.element.config.verticalAlign.value||null}},mediaUrl:function(){var e=i.a.MEDIA.previewMountain.slice(i.a.MEDIA.previewMountain.lastIndexOf("/")+1),n=this.assetFilter("administration/static/img/cms/".concat(e)),t=this.element.data.media,l=this.element.config.media;if("mapped"===l.source){var a=this.getDemoValue(l.value);return null!=a&&a.url?a.url:n}if("default"===l.source){var s=l.value.slice(l.value.lastIndexOf("/")+1);return this.assetFilter("/administration/static/img/cms/".concat(s))}return null!=t&&t.id?this.element.data.media.url:null!=t&&t.url?this.assetFilter(l.url):n},assetFilter:function(){return s.getByName("asset")},mediaConfigValue:function(){var e,n,t;return null===(e=this.element)||void 0===e||null===(n=e.config)||void 0===n||null===(t=n.sliderItems)||void 0===t?void 0:t.value}},watch:{cmsPageState:{deep:!0,handler:function(){this.$forceUpdate(),this.updateDemoValue()}},"element.config.content.source":{handler:function(){this.updateDemoValue()}},mediaConfigValue:function(e){var n,t,i,l,a,s,o=null===(n=this.element)||void 0===n||null===(t=n.data)||void 0===t||null===(i=t.media)||void 0===i?void 0:i.id;"static"===(null===(l=this.element)||void 0===l||null===(a=l.config)||void 0===a||null===(s=a.media)||void 0===s?void 0:s.source)&&o&&e!==o&&(this.element.config.media.value=o)}},created:function(){this.createdComponent()},methods:{createdComponent:function(){this.initElementConfig("gaisbock-image-titles-button"),this.initElementData("gaisbock-image-titles-button")},updateDemoValue:function(){"mapped"===this.element.config.content.source&&(this.demoValue=this.getDemoValue(this.element.config.content.value))},onBlur:function(e){this.emitChanges(e)},onInput:function(e){this.emitChanges(e)},emitChanges:function(e){e!==this.element.config.content.value&&(this.element.config.content.value=e,this.$emit("element-update",this.element))}}}},U6lq:function(e,n,t){var i=t("uLNG");i.__esModule&&(i=i.default),"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);(0,t("P8hj").default)("52e6dfb0",i,!0,{})},uLNG:function(e,n,t){}}]);