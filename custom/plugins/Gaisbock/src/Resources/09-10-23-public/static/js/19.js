(this.webpackJsonpPlugingaisbock=this.webpackJsonpPlugingaisbock||[]).push([[19],{XTXz:function(e,t,i){var n=i("dVLj");n.__esModule&&(n=n.default),"string"==typeof n&&(n=[[e.i,n,""]]),n.locals&&(e.exports=n.locals);(0,i("P8hj").default)("67fafe04",n,!0,{})},atLj:function(e,t,i){"use strict";i.r(t);i("XTXz");var n=i("0576"),a=Shopware,l=a.Mixin,s=a.Filter;t.default={template:'{% block sw_cms_element_category_image_text %}\n<div class="sw-cms-element-gaisbock-category-image-text">\n    <div class="sw-cms-element-gaisbock-category-image-text__images">\n        <div class="sw-cms-element-gaisbock-category-image-text__inner-images-text-one">\n            <span>{{ element.config.newTitle.value }}</span>\n            <img :src="mediaUrl">\n        </div>\n    </div>\n</div>\n{% endblock %}',inject:["feature"],mixins:[l.getByName("cms-element")],computed:{displayModeClass:function(){return"standard"===this.element.config.displayMode.value?null:"is--".concat(this.element.config.displayMode.value)},styles:function(){return{"min-height":"cover"===this.element.config.displayMode.value&&this.element.config.minHeight.value&&0!==this.element.config.minHeight.value?this.element.config.minHeight.value:"340px"}},imgStyles:function(){return{"align-self":this.element.config.verticalAlign.value||null}},mediaUrl:function(){var e=n.a.MEDIA.previewMountain.slice(n.a.MEDIA.previewMountain.lastIndexOf("/")+1),t=this.assetFilter("administration/static/img/cms/".concat(e)),i=this.element.data.media,a=this.element.config.media;if("mapped"===a.source){var l=this.getDemoValue(a.value);return null!=l&&l.url?l.url:t}if("default"===a.source){var s=a.value.slice(a.value.lastIndexOf("/")+1);return this.assetFilter("/administration/static/img/cms/".concat(s))}return null!=i&&i.id?this.element.data.media.url:null!=i&&i.url?this.assetFilter(a.url):t},assetFilter:function(){return s.getByName("asset")},mediaConfigValue:function(){var e,t,i;return null===(e=this.element)||void 0===e||null===(t=e.config)||void 0===t||null===(i=t.sliderItems)||void 0===i?void 0:i.value}},watch:{cmsPageState:{deep:!0,handler:function(){this.$forceUpdate()}},mediaConfigValue:function(e){var t,i,n,a,l,s,o=null===(t=this.element)||void 0===t||null===(i=t.data)||void 0===i||null===(n=i.media)||void 0===n?void 0:n.id;"static"===(null===(a=this.element)||void 0===a||null===(l=a.config)||void 0===l||null===(s=l.media)||void 0===s?void 0:s.source)&&o&&e!==o&&(this.element.config.media.value=o)}},created:function(){this.createdComponent()},methods:{createdComponent:function(){this.initElementConfig("gaisbock-category-image-text"),this.initElementData("gaisbock-category-image-text")}}}},dVLj:function(e,t,i){}}]);