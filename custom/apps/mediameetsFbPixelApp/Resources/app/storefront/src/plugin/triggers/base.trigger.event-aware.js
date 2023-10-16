import BaseTrigger from './base.trigger';

export default class BaseTriggerEventAware extends BaseTrigger
{
    execute() {
        const events = this.events();
        const pluginRegistry = window.PluginManager;

        Object.keys(events).forEach((pluginName) => {
            pluginRegistry.getPluginInstances(pluginName).forEach((pluginInstance) => {
                Object.keys(events[pluginName]).forEach((eventName) => {
                    pluginInstance.$emitter.subscribe(eventName, events[pluginName][eventName]);
                });
            });
        });
    }

    /**
     * @return {Object}
     */
    events() {
        this.warn('Method \'events\' was not overridden by `' + this.constructor.name + '`.');
    }
}
