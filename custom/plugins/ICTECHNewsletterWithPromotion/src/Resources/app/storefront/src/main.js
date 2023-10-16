import IctNewsletterPopup from './newsletter-popup/ictnewsletter-popup.plugin';
import IctNewsletterPopupForm from './newsletter-popup/ictnewsletter-popup-form.plugin';
// import TwoStepFormPlugin from "./newsletter-popup/two-step-form.plugin";

const PluginManager = window.PluginManager;
PluginManager.register('IctNewsletterPopup', IctNewsletterPopup, '[data-newsletter-popup]');
PluginManager.register('IctNewsletterPopupForm', IctNewsletterPopupForm, '[data-newsletter-popup-form]');
// PluginManager.register('TwoStepFormPlugin', TwoStepFormPlugin, '[data-two-step-form]')