import gaisbockAddmoreSection from './js/gaisbock-addmore-section.plugin';
import gaisbockAddRemoveClass from './js/gaisbock-add-remove-class.plugin';
import gaisbockThemeScrollSection from './js/gaisbock-theme-scroll-section-plugin';
import gaisbcksearchbar from './js/gaisbock-searchbar.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('gaisbockAddmoreSection',gaisbockAddmoreSection,'[data-gaisbock-addmore-section]');
PluginManager.register('gaisbockAddRemoveClass',gaisbockAddRemoveClass,'[data-gaisbock-add-remove-class]');
PluginManager.register('gaisbockThemeScrollSection',gaisbockThemeScrollSection,'[data-gaisbock-scroll-section]');
PluginManager.register('gaisbcksearchbar',gaisbcksearchbar,'[data-gaisbock-searchbar]');