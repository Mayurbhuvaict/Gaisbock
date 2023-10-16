const LOG_ENABLED = 'development' === process.env.NODE_ENV;

/**
 * This method is used to override plugin methods without extending them like Shopware wants.
 *
 * @param pluginName
 * @param methods
 */
export default function PluginOverride(pluginName, methods) {
    let plugin = null;

    try {
        plugin = window.PluginManager.getPlugin(pluginName);
    } catch (error) {
        if (LOG_ENABLED) {
            console.log('Warning: Trying to override non-registered plugin "%s".', pluginName);
        }

        return;
    }

    const classDefinition = plugin.get('class');

    for (let methodName in methods) {
        if (!methods.hasOwnProperty(methodName)) {
            continue;
        }

        const originalMethod = classDefinition.prototype[methodName];
        const methodOverride = methods[methodName];

        if (typeof originalMethod !== 'function') {
            if (LOG_ENABLED) {
                console.log('Warning: The given method "%s" is not known.', methodName);
            }

            continue;
        }

        classDefinition.prototype[methodName] = function() {
            if ('before' in methodOverride && typeof methodOverride.before === 'function') {
                methodOverride.before(this);
            }

            originalMethod.apply(this, arguments);

            if ('after' in methodOverride && typeof methodOverride.after === 'function') {
                methodOverride.after(this);
            }
        };
    }
}

window.Neti = window.Neti || {};
window.Neti.StoreLocator = window.Neti.StoreLocator || {};

window.Neti.StoreLocator.PluginOverride = PluginOverride;