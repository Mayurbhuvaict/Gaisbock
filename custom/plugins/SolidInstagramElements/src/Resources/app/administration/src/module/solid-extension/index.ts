import './component/solid-extension-data-handling-form'

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const { Locale } = Shopware;

Locale.extend('en-GB', enGB);
Locale.extend('de-DE', deDE);
