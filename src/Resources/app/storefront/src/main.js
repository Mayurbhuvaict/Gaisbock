import gaisbockAddmoreSection from './js/gaisbock-addmore-section.plugin';
import gaisbockAddRemoveClass from './js/gaisbock-add-remove-class.plugin';
import gaisbockThemeScrollSection from './js/gaisbock-theme-scroll-section-plugin';
import gaisbockimageslider from "./js/gaisbock-image-slider.plugin";
import gaisbcksearchbar from './js/gaisbock-searchbar.plugin';
import gaisbockHeaderHoverAddClass from './js/gaisbock-header-hover-add-class.plugin';
import gaisbockRefreshPage from './js/gaisbock-refresh-page.plugin';
import gaisbockMenuButtonClass from './js/gaisbock-menu-button-class.plugin';

const PluginManager = window.PluginManager;
PluginManager.register('gaisbockAddmoreSection',gaisbockAddmoreSection,'[data-gaisbock-addmore-section]');
PluginManager.register('gaisbockAddRemoveClass',gaisbockAddRemoveClass,'[data-gaisbock-add-remove-class]');
PluginManager.register('gaisbockThemeScrollSection',gaisbockThemeScrollSection,'[data-gaisbock-scroll-section]');
PluginManager.register('gaisbockimageslider',gaisbockimageslider,'[data-gaisbock-image-slider]');
PluginManager.register('gaisbcksearchbar',gaisbcksearchbar,'[data-gaisbock-searchbar]');
PluginManager.override('FlyoutMenu',gaisbockHeaderHoverAddClass,'[data-flyout-menu]');
PluginManager.register('gaisbockRefreshPage',gaisbockRefreshPage,'[data-refresh-page]');
PluginManager.override('OffcanvasMenu',gaisbockMenuButtonClass,'[data-offcanvas-menu]');
