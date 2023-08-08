import gaisbockAddmoreSection from './js/gaisbock-addmore-section.plugin';
import gaisbockAddRemoveClass from './js/gaisbock-add-remove-class.plugin';
import gaisbockThemeScrollSection from './js/gaisbock-theme-scroll-section-plugin';
import gaisbockimageslider from "./js/gaisbock-image-slider.plugin";

const PluginManager = window.PluginManager;
PluginManager.register('gaisbockAddmoreSection',gaisbockAddmoreSection,'[data-gaisbock-addmore-section]');
PluginManager.register('gaisbockAddRemoveClass',gaisbockAddRemoveClass,'[data-gaisbock-add-remove-class]');
PluginManager.register('gaisbockThemeScrollSection',gaisbockThemeScrollSection,'[data-gaisbock-scroll-section]');
PluginManager.register('gaisbockimageslider',gaisbockimageslider,'[data-gaisbock-image-slider]')