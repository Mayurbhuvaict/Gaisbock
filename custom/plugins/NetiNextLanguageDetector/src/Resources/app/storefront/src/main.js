import LanguageDetectorPlugin from './language-detector/language-detector.plugin';

const PluginManager = window.PluginManager;

PluginManager.register('LanguageDetectorPlugin', LanguageDetectorPlugin, 'body');
