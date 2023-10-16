# 4.20.0

- [#43443] Implement Google LocalBusiness tags at store detail page

# 4.19.0

- [#43252] New plugin config: Inherit map language from sales channel domain
- [#43368] Use interface of GenericPageLoader

# 4.18.10

- [#42849] Improved validation of errors for opening times
- [#42849] Corrected country assignment when importing data

# 4.18.9

- [#42738] Fixed issue with updating coordinates in the administration

# 4.18.8

- [#42677] Fixed issue when clicking a store outside of the search
- [#42495] Improved loading of plugin config

# 4.18.7

- [#42567] Fixed an exception when loading stores
- [#42566] Fixed an issue with missing styling for autocomplete results

# 4.18.6

- [#42545] Fixed an issue where the icons was invisible
- [#42534] Opening hours are sorted
- [#42437] Fixed a display issue for mobile devices
- [#42436] Fixed some bugs according the search of the store locator
- [#42329] Fixed formatting of HTML in the additional information of stores
- [#42331] Improved performance with many stores

# 4.18.5

- [#42410] Fixed an error when updating the coordinates in the administration
- [#42354] Improved saving of the business hours

# 4.18.4

- [#42336] Fixed compile error (math.div)
- [#42341] Correct output of the date for special times

# 4.18.3

- [#42244] Fixed an issue with the cms page layouts in the administration
- [#42239] Fixed an issue with the storefront build process for composer installations

# 4.18.2

- [#42221] Fixed an issue with the cookie consent for StorePickup
- [#42217] Fixed an error when calling up detail pages for the first time without a time zone

# 4.18.1

- [#42186] Fix issue on detail page

# 4.18.0

- [#42136] Fix using division outside of calc()
- [#42091] Fixed a logical issue according to the customField filter
- [#42073] RepositoryDecorator no longer overwrites ID's on API calls
- [#42126] Improved sidebar menu design
- [#41964] Improved storefront build process
- [#41860] Added plugin config: Store fields for search input
- [#41399] New function: Cookie consent interface

# 4.17.0

- [#41856] Added plugin config: Show filters permanently
- [#41976] Fixed bug when clicking on the store on the map

# 4.16.1

- [#41636] Fixed scrolling to store when selecting store in autocomplete results
- [#41632] Fixed invalid constructor call in events

# 4.16.0

- [#41438] Improved "update coordinates" modal
- [#41481] New plugin config: Google Map ID
- [#41403] New plugin config: Order of search results

# 4.15.2

- [#41419] Fixed issue with the cookie consent in the storefront
- [#41424] Upgrade to @googlemaps/markerclusterer

# 4.15.1

- [#41318] Improved presentation of the opening hours in the listing and in the info box.

# 4.15.0

- [#41293] Fixed business hours help text
- [#41266] Added new plugin config: Allow search only with keywords
- [#41227] Extended the javascript component for global access
- [#41226] New plugin config: Individual google map parameters

# 4.14.5

- [#41150] Optimize the view of "Featured" and "Selected" stores
- [#41256] Optimize max search result limit in the storefront
- [#41197] Improved highlighting of selected store in the store locator
- [#41259] Support boolean custom fields for the storefront filter

# 4.14.4

- [#41254] Stores without time zones are displayed again

# 4.14.3

- [#41151] Extensions of the stores are extended by the opening hours and no longer replaced
- [#41183] Adjust position of map overlay

# 4.14.2

- [#41140] Improved texts in the administration
- [#41149] Improved time zone comparison
- [#35790] Add help texts in the admin
- [#41131] Adaptation for validation of opening times

# 4.14.1

- [#41117] Fixed issue in storefront

# 4.14.0

- [#36141] You can now duplicate stores in the administration
- [#40982] New plugin config: Cookie lifetime
- [#40457] Improved display of info box when no stores are loaded yet
- [#40470] New store configuration: alternative image for detail page
- [#38171] Improved management of business hours in the administration (StoreBusinessHours)

# 4.13.5

- [#40949] Added privacy information in the contact form

# 4.13.4

- [#40817] Adjustments for StorePickup

# 4.13.3

- [#40685] The seo template is now deleted when uninstalling the plugin
- [#40687] Fixed issue where an artefact was shown below the map

# 4.13.2

- [#40679] Improved compatibility with the changed cookie manager from Shopware
- [#40653] Flows are no longer created multiple times by BusinessEvents

# 4.13.1

- [#40599] Fixed issue where no marker content was displayed
- [#40601] Fixed issue when reading plugin configuration

# 4.13.0

- [#40549] Fixed issue when saving filters
- [#40400] Added timezone request from google per store
- [#40296] Integration of flow events
- [#40489] Fundamental improvement of the usability of the StoreLocator in connection with disabled initial listing
- [#40487] New plugin config: Filter mode for tags
- [#40452] Fixed issue on language switch
- [#40530] Fixed issue with filtering stores with "Show always"
- [#40470] New store configuration: alternative image for detail page

# 4.12.0

- [#38168] Compatibility changes for PWA

# 4.11.0

- [#40333] Fix United Kinkdom address formating
- [#40334] New function: show nearest stores on checkout finish page
- [#40307] New type for contact form: File attachment
- [#40278] Added canonical tag for listing and detail pages
- [#40356] Contact form fields could not be deleted

# 3.10.16

- [#41183] Adjust position of map overlay

# 3.10.15

- [#40949] Added privacy information in the contact form

# 3.10.14

- [#40356] Contact form fields could not be deleted

# 3.10.12

- [#40128] Optimized import with empty tags

# 3.10.11

- [#39198] The store search was improved

# 3.10.10

- [#38977] Fixed dropdown over the whole page for country entries

# 3.10.9

- [#38642] Improved height calculation of the map in the mobile area

# 3.10.8

- [#38369] "store.featured" was read incorrectly in the store.twig template

# 3.10.7

- [#38011] Fix of the JavaScript error that got thrown in the console because of the empty streetnumber

# 3.10.6

- [#37969] Fixed problem with untranslated values in the contact form

# 3.10.5

- [#37753] Remove street number as mandatory field
- [#37776] Fixed problem with untranslated labels in the contact form

# 3.10.4

- [#37635] Stores without coordinators are no longer loaded in the storefront

# 4.10.3

- [#40277] The default search country is now read from plugin config only
- [#40251] Limit custom field compatibility to the following field types: Select field, Text, Color, Date & Number

# 3.10.3

- [#37490] Fixed error in migration

# 4.10.2

- [#40148] Filter: Improved compatibility with custom fields
- [#40178] Tables can now be deleted on uninstall
- [#40184] Compatibility established with Shopware 6.4.10.x

# 3.10.2

- [#37407] Improved usability of the mobile version
- [#37447] Fixed issue with the contact form

# 4.10.1

- [#40159] Fixed issue with z-index and off canvas filter
- [#40128] Optimized import with empty tags

# 3.10.1

- [#37359] Opening times could not be saved

# 4.10.0

- [#40103] Fixed spacing on button group
- [#40051] Additional information as HTML
- [#40129] Media is now saved in the media folder "StoreLocator"
- [#40127] Added js events that are called when the off canvas filter is opened or closed
- [#40109] New plugin config: Show filter in off canvas (only for enabled custom filters)
- [#40073] New plugin config: Clear search input when selected country is changed

# 3.10.0

- [#37191] META title and description can now be maintained via snippets
- [#37198] Error fixed when location detection was deactivated by the browser

# 4.9.5

- [#40089] Reverted fix "40009"
- [#40072] Fixed issue at sorting the countries
- [#40083] Filter: Fixed logical issue

# 4.9.4

- [#39939] Fixed global search in administration
- [#40053] Fixed issue in storefront filter with custom fields

# 4.9.3

- [#40009] fixed z-index issue
- [#40022] Linking of the e-mail address in the StoreLocator

# 4.9.2

- [#40000] fixed z-index issue

# 3.9.2

- [#37132] Adjustments for NetiNextStoreManager
- [#37153] Fixed issue with the store entity

# 4.9.1

- [#39998] Fixed issue in storefront

# 3.9.1

- [#37113] Added missing "page" variable when rendering the CMS page

# 4.9.0

- [#34996] New feature: Individual filter in the storefront
- [#39762] Adjustments for the StoreSelect feature in StorePickup
- [#39938] You can now use HTML in the opening times field

# 3.9.0

- [#36777] New filter options have been added

# 4.8.0

- [#39834] Fixed search in administration
- [#39820] Improved map zoom if only 1 result is available

# 3.8.0

- [#36828] GDPR Compatibility
- [#36886] Added plugin configuration to deactivate the "Auto-Complete" function of Google Maps

# 3.7.1

- [#36962] Error in migration fixed

# 4.7.0

- [#39691] Stores can now be filtered in the administration

# 3.7.0

- [#36778] Integration of the BusinessEvents improved
- [#36884] Consideration of the fallback language when displaying the opening times
- [#36803] Information is now displayed in the store administration if no custom fields have been configured.

# 4.6.2

- [#39600] Fixed issue with migration
- [#39561] Adjusted snippets

# 4.6.1

- [#39469] Fixed logical issue when sorting the countries

# 3.6.1

- [#36802] Problem solved with AutoComplete
- [#36799] Added maps language parameter to config

# 4.6.0

- [#39404] New translation for dutch (admin & storefront)

# 3.6.0

- [#36795] Missing field "E-Mail" added in administration
- [#36782] New field for opening hours added as well as new related plugin configuration
- [#36772] Fixed issue when creating a new store
- [#36764] Various Twig blocks added for improved extensibility of the templates
- [#36763] Added missing properties to the StoreEntity (CountryState)
- [#36615] Search implemented via GET parameter
- [#36773] Fixed broken detail page in case no html content was set.
- [#36790] "Copy to me" in the contact form did not work
- [#36789] Faulty database migration improved
- [#36788] Handover of the tags to the storefront

# 4.5.1

- [#39440] Suppressing an error message if no stores are available

# 5.5.0

- [#43495] Multiple cms pages can now be assigned to a store
- [#43443] Implement Google LocalBusiness tags at store detail page

# 4.5.0

- [#39322] New plugin config: Pre-selected country for the filter
- [#39321] Improved country sort in store filter
- [#39376] New function: selection of the active category for the StoreLocator page

# 3.5.0
- [#36606] Compatibility adjustments for StorePickup
- [#36438] Integrated custom fields for the stores
- [#36418] Added StoreDetailPageLoadedEvent and StoreListingPageLoadedEvent

# 3.4.6
- [#36417] Individual SEO URLs per store did not work

# 3.4.5

- [#36411] Fixed issue with seo url

# 3.4.4

- [#36248] Additional fields for the import / export profile

# 3.4.3

- [#36110] Fixed "-0 Coordinates not possible"

# 4.4.2

- [#39108] Load stores using the repository iterator
- [#39198] The store search was improved

# 3.4.2

- [#35820] Fixed configurations informations
- [#35819] Bug fixes and performance improvements
- [#35616] Fixed problems importing from other shops
- [#35798] Moved snippet files into new file structure

# 4.4.1

- [#38977] Fixed dropdown over the whole page for country entries

# 3.4.1

- [#35778] Fixed problems importing from other shops
- [#35731] Fixed invalid class constructor

# 5.4.0

- [#42383] Added new plugin config: Show local marker
- [#42289] Improved update coordinates modal in administration

# 4.4.0

- [#37501] Added store search as a CMS element
- [#38905] Fixed issue in an entity

# 3.4.0

- [#35616] Added field "country state" to the address

# 4.3.1

- [#38642] Improved height calculation of the map in the mobile area

# 5.3.0

- [#43035] New plugin config: Filter highlighted stores by radius first
- [#43252] New plugin config: Inherit map language from sales channel domain
- [#43368] Use interface of GenericPageLoader

# 4.3.0

- [#38600] New function: Sorting of countries + optional country filter in the store locator

# 3.3.0

- [#35136] Map-Preview in the administration
- [#35134] Shopping Experiences on the StoreLocator page

# 2.2.11

- [#38369] "store.featured" was read incorrectly in the store.twig template

# 2.2.10

- [#37969] Fixed problem with untranslated values in the contact form

# 2.2.9

- [#37776] Fixed problem with untranslated labels in the contact form

# 2.2.8

- [#37635] Stores without coordinators are no longer loaded in the storefront

# 2.2.7

- [#36659] "Copy to me" in the contact form did not work
- [#36714] Faulty database migration improved
- [#36757] Handover of the tags to the storefront
- [#36773] Fixed broken detail page in case no html content was set.

# 2.2.6
- [#36357] Fixed issue with seo url

# 2.2.5
- [#36215] Additional fields for the import / export profile

# 2.2.4
- [#36081] Fixed "-0 Coordinates not possible"

# 2.2.3
- [#35789] Fixed configurations informations
- [#35750] Bug fixes and performance improvements

# 5.2.2

- [#43343] Fixed issue where the cms page were not shound on the detail page

# 2.2.2
- [#35672] Fixed problems importing from other shops

# 5.2.1

- [#43278] When importing, the ID of a store with the same external ID is taken

# 3.2.1

- [#35421] Fixed visibility bug in Firefox
- [#35419] Add missing snippet
- [#35420] Improve usability when using an invalid api key

# 2.2.1
- [#35375] Fixed visibility bug in Firefox
- [#35384] Add missing snippet
- [#35376] Improve usability when using an invalid api key

# 5.2.0

- [#43093] Improved handling of sales channels during import
- [#43092] Added event GetStoresEvent

# 4.2.0

- [#38583] New filter: Show only featured stores
- [#38574] Improvement of the SeoResolverDecorator

# 3.2.0

- [#35349] Import/export profile added for the stores from SW 5 StoreLocator export

# 2.2.0
- [#35047] Import/export profile added for the stores from SW 5 StoreLocator export

# 3.1.1

- [#35262] Increase limit in the country selection in the administration
- [#35261] Storefront Edge/IE Support

# 2.1.1
- [#35234] Increase limit in the country selection in the administration
- [#35239] Storefront Edge/IE Support

# 5.1.0

- [#42868] Extended filter for the stores in the administration
- [#42926] Small further adjustments for Shopware 6.5

# 4.1.0

- [#38237] New optional field: external id
- [#38369] "store.featured" was read incorrectly in the store.twig template

# 3.1.0

- [#35072] Import/export profile added for the stores

# 2.1.0
- [#34944] Import/export profile added for the stores

# 4.0.8

- [#38011] Fix of the JavaScript error that got thrown in the console because of the empty streetnumber

# 4.0.7

- [#37969] Fixed problem with untranslated values in the contact form

# 4.0.6

- [#37966] Fixed issue in migration

# 4.0.5

- [#37753] Remove street number as mandatory field
- [#37776] Fixed problem with untranslated labels in the contact form

# 4.0.4

- [#37635] Stores without coordinators are no longer loaded in the storefront

# 4.0.3

- [#37490] Fixed error in migration

# 4.0.2

- [#37407] Improved usability of the mobile version
- [#37447] Fixed issue with the contact form

# 5.0.1

- [#42881] Corrected country assignment when importing data

# 4.0.1

- [#37355] Media selection in the administration did not work

# 5.0.0

- [#42488] Compatibility with Shopware 6.5

# 4.0.0

- [#37241] Compatibility with Shopware 6.4

# 3.0.0

- [#34943] Shopware 6 Compatibility

# 2.0.0

- [#34714] Initial release for Shopware 6

