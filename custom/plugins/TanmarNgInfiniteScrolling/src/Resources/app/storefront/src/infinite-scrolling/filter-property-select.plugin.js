import FilterPropertySelectPlugin from 'src/plugin/listing/filter-property-select.plugin'

export default class TanmarInfiniteScrollingFilterPropertySelectPlugin extends PluginManager.getPlugin('FilterPropertySelect').get('class') {

    /**
     * @private
     */
    _onChangeFilter() {
        var me = this, l = me.listing;
        
        if (l._tmisActive) {
            l._tmisListingOption = 'override';
            l._visitedPagesClear();
            l._tmisNewPageRequestCounter = 0;
            l._tmisIsLoading = true;
            l._tmisLog('  reset property _onChangeFilter');
            l.changeListing();
        } else {
            super._onChangeFilter();
        }
    }
}
