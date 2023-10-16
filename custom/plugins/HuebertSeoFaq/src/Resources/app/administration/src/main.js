import './module/sw-cms/blocks/faq/faq';
import './module/sw-cms/component/sw-cms-sidebar';
import './module/sw-cms/elements/faq';

import './module/hueb-seo-faq';

//import './init/svg-icons.init';

import deDE from './module/sw-cms/snippet/de-DE';
import enGB from './module/sw-cms/snippet/en-GB';

Shopware.Locale.extend('de-DE', deDE);
Shopware.Locale.extend('en-GB', enGB);

