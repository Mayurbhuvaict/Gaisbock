import FilterRangePlugin from 'src/plugin/listing/filter-range.plugin';

export default class TanmarInfiniteScrollingFilterRangePlugin extends PluginManager.getPlugin('FilterRange').get('class') {

    /**
     * @private
     */
    _onChangeInput() {
        var me = this, l = me.listing;
        
        if (l._tmisActive) {
            clearTimeout(me._timeout);

            me._timeout = setTimeout(function(){
                var me = this, l = me.listing;
                
                if (me._isInputInvalid()) {
                    me._setError();
                } else {
                    me._removeError();
                }
                l._tmisListingOption = 'override';
                l._visitedPagesClear();
                l._tmisNewPageRequestCounter = 0;
                l._tmisIsLoading = true;
                l._tmisLog('  reset _onChangeInput');
                l.changeListing();
            }.bind(me), me.options.inputTimeout);
        }else{
            super._onChangeInput();
        }
    }

}
