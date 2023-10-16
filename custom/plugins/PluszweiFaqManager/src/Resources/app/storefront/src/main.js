import SearchArticlePlugin from "./plugin/faq/search-article.plugin";
import FaqSectionPlugin from "./plugin/faq/faq-section.plugin";

const PluginManager = window.PluginManager;

PluginManager.register('SearchArticle', SearchArticlePlugin, '[data-search-article]');
PluginManager.register('FaqSection', FaqSectionPlugin, '[data-faq-section]');