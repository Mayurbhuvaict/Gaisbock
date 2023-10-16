import SeoFaq from './seoFaq/seo-faq.plugin';
import SeoFaqElement from "./seoFaq/seo-faq-element.plugin";

const PluginManager = window.PluginManager;
PluginManager.register('SeoFaq', SeoFaq, '[js-SeoFaq]');
PluginManager.register('SeoFaqElement', SeoFaqElement);
