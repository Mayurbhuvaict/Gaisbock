import AcrisRecentlyViewedPlugin from "./plugin/acris-recently-viewed/acris-recently-viewed.plugin";
import AcrisCustomersAlsoViewedPlugin from "./plugin/acris-customers-also-viewed/acris-customers-also-viewed.plugin";

window.PluginManager.register('AcrisRecentlyViewedPlugin', AcrisRecentlyViewedPlugin, '[data-acris-recently-viewed-plugin]');
window.PluginManager.register('AcrisCustomersAlsoViewedPlugin', AcrisCustomersAlsoViewedPlugin, '[data-acris-customers-also-viewed-plugin]');
