import SolidIEFeedPlugin from './plugin/instagram-elements/solid-ie-feed.plugin';

const PluginManager = window.PluginManager;
PluginManager.register(
    'SolidIEFeedPlugin',
    SolidIEFeedPlugin,
    '.solid-ie-feed-meta-cdn'
);
