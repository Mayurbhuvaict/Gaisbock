import gaisbockAddmoreSection from './js/gaisbock-addmore-section.plugin';
import gaisbockAddRemoveClass from './js/gaisbock-add-remove-class.plugin';


const PluginManager = window.PluginManager;
PluginManager.register('gaisbockAddmoreSection',gaisbockAddmoreSection,'[data-gaisbock-addmore-section]');
PluginManager.register('gaisbockAddRemoveClass',gaisbockAddRemoveClass,'[data-gaisbock-add-remove-class]')