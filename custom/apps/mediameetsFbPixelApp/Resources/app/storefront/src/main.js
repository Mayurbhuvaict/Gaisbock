import mediameetsFacebookPixelPlugin from './plugin/mediameets-facebook-pixel.plugin';

const PluginManager = window.PluginManager;

PluginManager.register('mediameetsFacebookPixel', mediameetsFacebookPixelPlugin, '[data-mediameets-facebook-pixel-options]');

if (module.hot) {
    module.hot.accept();
}
