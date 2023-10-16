import FilterRatingSelectPlugin from 'src/plugin/listing/filter-rating-select.plugin'

export default class TanmarInfiniteScrollingFilterRatingSelectPlugin extends PluginManager.getPlugin('FilterRatingSelect').get('class') {

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
            l._tmisLog('  reset property _onChangeRating');
            l.changeListing();
        } else {
            super._onChangeFilter();
        }
    }
}
