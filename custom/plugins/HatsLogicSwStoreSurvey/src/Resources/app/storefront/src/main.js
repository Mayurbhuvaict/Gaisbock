// Import all necessary Storefront plugins and scss files
import HlSwStoreSurveyPlugin from './hl-store-survey/hl-store-survey-plugin.js';
import hlEmotionRating from './hl-store-survey/emotion-ratings-plugin.js';

// Register them via the existing PluginManager
const PluginManager = window.PluginManager;
PluginManager.register('HlSwStoreSurveyPlugin', HlSwStoreSurveyPlugin,'[data-hl-store-survey="true"]');
PluginManager.register('hlEmotionRating', hlEmotionRating,'[data-hl-emotion-rating="true"]');
