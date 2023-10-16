import ListingPlugin from 'src/plugin/listing/listing.plugin';
import ElementReplaceHelper from 'src/helper/element-replace.helper';
import PluginManager from 'src/plugin-system/plugin.manager';
import LoadingIndicatorUtil from 'src/utility/loading-indicator/loading-indicator.util';

// noinspection DuplicatedCode
/**
 * @author Tanmar Webentwicklung <info@tanmar.de>
 * @version 1.5.1
 */
export default class TanmarInfiniteScrolling extends PluginManager.getPlugin('Listing').get('class') {

    init() {
        var me = this;

        //
        me._tmisVersion = '1.5.1';

        // debug for logs
        me._tmisDebug = !!window._tanmarInfiniteScrolling && !!window._tanmarInfiniteScrolling.debug;

        // plugin is active
        me._tmisActive = false;

        // page counter, only load one automatically, show box after that
        me._tmisNewPageRequestCounter = 0;

        // loading indicator
        me._tmisIsLoading = false;

        // get listing html, append prev/next box
        me._tmisListingElement = null;

        // type how to add listing html
        me._tmisListingOption = '';

        // snippet holder
        me._tmisSnippets = {};

        me._tmisNewPageRequestMax = 1;

        me._listingRowSelector = '.cms-element-product-listing-wrapper .cms-listing-row';

        me._paginationResponseSelector = '.cms-element-product-listing-wrapper .pagination-nav';

        me._paginationSelector = '.pagination-nav';
        
        me._triggerAfterRenderResponseEvent = false;
        me._onlyObserveWithinListingWrapper = false;
        me._customProductSelector = '';

        me.iObserver = false;
        
        // disable scroll top for infinite scrolling
        me.options.scrollTopListingWrapper = false;

        // super init
        super.init();

        me._tmisInit();
    }

    _tmisInit(){
        var me = this;

        me._tmisLog('TanmarInfiniteScrolling v' + me._tmisVersion);

        if(!document.querySelector('body.is-tanmar-infinite-scrolling')){
            me._tmisLog('  error: css class "is-tanmar-infinite-scrolling" in body not found');
            this._tmisDestroy();
            return; // inactive
        }

        if(!window._tanmarInfiniteScrolling){
            me._tmisLog('  error: cant find window._tanmarInfiniteScrolling');
            this._tmisDestroy();
            return;
        }

        // load snippets
        me._tmisLog('  config ', window._tanmarInfiniteScrolling);

        me._tmisNewPageRequestMax = parseInt(window._tanmarInfiniteScrolling.pages, 10);
        me._tmisSnippets = window._tanmarInfiniteScrolling.snippets;
        me._tmisLog('  snippets success ', me._tmisSnippets);

        me._triggerAfterRenderResponseEvent = window._tanmarInfiniteScrolling.triggerAfterRenderResponseEvent;
        me._onlyObserveWithinListingWrapper = window._tanmarInfiniteScrolling.onlyObserveWithinListingWrapper;
        me._customProductSelector = window._tanmarInfiniteScrolling.customProduct;
        
        let _paginationSelector = window._tanmarInfiniteScrolling.customPaginationSelector+'';
        if(_paginationSelector.trim() != ''){
            me._paginationSelector = _paginationSelector;
        }
        
        // page history
        if(!window._tanmarInfiniteScrolling.visitedPages){
            window._tanmarInfiniteScrolling.visitedPages = [];
        }

        me._tmisActive = true;

        // hide navigation
        var paginations = document.querySelectorAll(me._paginationSelector);
        if(paginations.length <= 0){
            me._tmisLog('  error - no pagination found: "' + me._paginationSelector + '" not found');
            this._tmisDestroy();
            return;
        }
        paginations.forEach(nav => {
            nav.classList.add('tmis-d-none');
        });

        // register intersection observer
        me._tmisRegisterIntersectionObserver();

        //
        me._tmisSetCurrentAndLastPage(paginations);

        if(me.lastPage > me.currentPage){
            me._tmisLog('  register oberver on init');
            me._tmisObserveLastProductBox();
        }

        // add current page to history
        me._visitedPagesAdd(me.currentPage);

        // get listing html
        me._tmisListingElement = document.querySelector(this.options.cmsProductListingSelector);

        me._tmisLog('  currentPage = ' + me.currentPage + ' - lastPage = ' + me.lastPage + ' - visitedPages:');
        me._tmisLog(window._tanmarInfiniteScrolling.visitedPages);

        // check for button on top
        if(me.currentPage > 1){
            me._tmisBuildPrevInfoBox();
        }

        // immediately show info box on max 0
        if(me._tmisNewPageRequestMax == 0){
            me._tmisBuildNextInfoBox();
        }

    }

    _tmisSetCurrentAndLastPage(paginations){
        var me = this;

        let currentPage = 1;
        let lastPage = 1;

        if(paginations && paginations[0]){
            let currentPageInput = paginations[0].querySelector('.page-item.active input');
            if(currentPageInput){
                currentPage = parseInt(currentPageInput.value, 10);
            }else{
                me._tmisLog('  can\'t find \'page-item.active input\'');
            }

            let lastPageInput = paginations[0].querySelector('.page-item.page-last input');
            if(lastPageInput){
                lastPage = parseInt(lastPageInput.value, 10);
            }else{
                me._tmisLog('  can\'t find \'page-item.page-last input\'');
            }
            
            
            me.currentPage = currentPage;
            me.lastPage = lastPage;
        }

    }

    _visitedPagesAdd(page){
        if(this._visitedPagesIndexOf(page) < 0){
            window._tanmarInfiniteScrolling.visitedPages.push(page);
        }
    }

    _visitedPagesIndexOf(page){
        return window._tanmarInfiniteScrolling.visitedPages.indexOf(page);
    }

    _visitedPagesClear(){
        window._tanmarInfiniteScrolling.visitedPages = [];
    }

    /**
     *
     */
    _tmisLog(){
        if(this._tmisDebug){
            console.log(...arguments);
        }
    }

    /**
     *
     */
    _tmisRegisterIntersectionObserver(){
        var options = {
            root: null,
            rootMargin: window._tanmarInfiniteScrolling.rootMargin || '0px',
            threshold: this._tmisSafeThreshold(window._tanmarInfiniteScrolling.threshold || 0.5),
        };
        this.iObserver = new IntersectionObserver(this._tmisOnIntersection.bind(this), options);
    }

    _tmisSafeThreshold(input){
        var a;
        try{
            a = JSON.parse(input);
        }catch(e){
            a = input;
        }

        if(Array.isArray(a)){
            return a;
        }else{
            var b = parseFloat(a, 10);
            if(!isNaN(b)){
                return b;
            }
        }
        return 0.5;
    }

    _getAllProductBoxes(){
        let allProductsSelector = '.card.product-box';
        if(this._customProductSelector && this._customProductSelector != ''){
            allProductsSelector = this._customProductSelector;
        }
        if(this._onlyObserveWithinListingWrapper){
            allProductsSelector = '.cms-element-product-listing-wrapper ' + allProductsSelector;
        }
        return document.querySelectorAll(allProductsSelector);
    }
    
    /**
     *
     */
    _tmisObserveLastProductBox(){
        var all = [];
        try {
            all = this._getAllProductBoxes();
        } catch (e) {
            
        }
        
        if(all.length <= 0){
            this._tmisLog('  error "last product" not found');
            this._tmisDestroy();
            return;
        }

        var lastProduct = all[all.length-1];
        this._tmisLog('  observe following element');
        this._tmisLog(lastProduct);

        if(!this.iObserver){
            this._tmisRegisterIntersectionObserver();
        }
        this.iObserver.observe(lastProduct);
        return lastProduct;
    }

    /**
     *
     */
    _tmisOnIntersection(entries, observer){
        var me = this;

        // on each intersection obj
        entries.forEach(entry => {

            // on "in view"
            if (entry.intersectionRatio > 0 && !me._tmisIsLoading) {
                
                // check navigation html
                const next = document.querySelector('.pagination .page-next');
                if(next){

                    me._tmisLog('  on intersection');

                    if(!next.classList.contains('disabled')){
                        if(me._tmisNewPageRequestCounter < me._tmisNewPageRequestMax){

                            me._tmisRequestNewPage(next, 'append');

                            me._tmisLog('  request new page, unobserve element');
                            me._tmisLog(entry.target);

                            observer.unobserve(entry.target);
                        }

                    }else{
                        me._tmisLog('  no new page, unobserve element');
                        me._tmisLog(entry.target);

                        // no next page, remove observer
                        observer.unobserve(entry.target);
                    }
                }

            }

        });

    }

    /**
     *
     */
    _tmisRequestNewPage(nextOrPrev, listingOption){
        var me = this;

        me._tmisIsLoading = true;

        me._tmisLog('  Request new page');

        // set option
        switch (listingOption) {
            case 'append':
                me._tmisListingOption = 'append';
                break;
            case 'prepend':
                me._tmisListingOption = 'prepend';
                break;
            default:
            case 'override':
                me._tmisListingOption = 'override';
                break;
        }


        // get next/prev page or fix page nr
        var nextPage;
        if(isNaN(nextOrPrev)){
            nextPage = parseInt(nextOrPrev.querySelector('input').value, 10);
        }else{
            nextPage = parseInt(nextOrPrev, 10);
        }

        // load listing pagination plugin
        const ListingPaginationPlugin = PluginManager.getPluginInstanceFromElement(document.querySelector('[data-listing-pagination]'),'ListingPagination');

        // request new page
        ListingPaginationPlugin.onChangePage({target:{value:nextPage}});

        // increase page request counter
        me._tmisNewPageRequestCounter++;

        // create and append loading
        me._tmisBuildLoading();
    }

    /**
     *
     */
    _tmisBuildLoading(){
        var me = this;

        const div = document.createElement('div');
        div.classList.add('text-center');
        div.classList.add('infinite-scrolling-loading');

        const loader = new LoadingIndicatorUtil(div);

        var listing = document.querySelectorAll(me._listingRowSelector);
        const listingLast = listing[listing.length - 1];

        // check direction
        switch (me._tmisListingOption) {
            case 'append':
                listingLast.parentNode.insertBefore(div, listingLast.nextSibling);
                break;
            case 'prepend':
            default:
                listing[0].parentNode.insertBefore(div, listing[0]);
                break;
        }

        loader.create();
    }

    /**
     *
     */
    _tmisBuildPrevInfoBox(){
        var me = this;

        if(document.querySelector('.infinite-scrolling-button-prev') !== null){
            return;
        }

        const div = document.createElement('div');
        div.classList.add('text-center');
        div.classList.add('infinite-scrolling-button-prev');

        var prevPage = me.currentPage - 1;

        if(prevPage > 0){
            const width = prevPage/(me.lastPage>0?me.lastPage:1)*100;

            var naviSnippet = me._tmisSnippets.prev.navi.split('{x}').join(prevPage).split('{y}').join(me.lastPage);

            // box template
            div.innerHTML = `<button class="btn btn-block btn-buy">${me._tmisSnippets.prev.btn}</button>
                            <span class="tanmar-infinity-scrolling-button-text">
                                <span>${naviSnippet}</span>
                                <span class="tanmar-infinity-scrolling-button-bar">
                                    <span style="width: ${width}%"></span>
                                </span>
                            </span>`;

            if(window._tanmarInfiniteScrolling.customPrepend != ''){
                let cs = document.querySelector(window._tanmarInfiniteScrolling.customPrepend);
                if(cs !== null){
                    cs.appendChild(div);
                }
            }else{
                me._tmisListingElement.insertBefore(div, me._tmisListingElement.firstChild);
            }

            var btn = document.querySelector('.infinite-scrolling-button-prev button');
            if(btn){
                btn.addEventListener('click', function(){
                    var me = this[0];
                    var targetPage = this[1];

                    if(!me._tmisIsLoading){
                        me._tmisRequestNewPage(targetPage, 'prepend');

                        // remove box
                        const div = document.querySelector('.infinite-scrolling-button-prev');
                        div.parentNode.removeChild(div);
                    }
                }.bind([me,prevPage]));
            }
        }
    }

    /**
     *
     */
    _tmisBuildNextInfoBox(){
        var me = this;

        if(document.querySelector('.infinite-scrolling-button-more') !== null){
            return;
        }

        var nextPage = parseInt(me.currentPage,10) + 1;

        me._tmisLog('  nextPage = ' + nextPage + ' lastPage = ' + me.lastPage);


        // only build if next page wasnt loaded
        if(me._visitedPagesIndexOf(nextPage) < 0 && nextPage <= me.lastPage){

            const div = document.createElement('div');
            div.classList.add('text-center');
            div.classList.add('infinite-scrolling-button-more');

            // current page, not next page
            const width = me.currentPage/(me.lastPage>0?me.lastPage:1)*100;

            var naviSnippet = me._tmisSnippets.next.navi.split('{x}').join(nextPage).split('{y}').join(me.lastPage);
            // box template
            div.innerHTML = `<button class="btn btn-block btn-buy">${me._tmisSnippets.next.btn}</button>
                            <span class="tanmar-infinity-scrolling-button-text">
                                <span>${naviSnippet}</span>
                                <span class="tanmar-infinity-scrolling-button-bar">
                                    <span style="width: ${width}%"></span>
                                </span>
                            </span>`;

            if(window._tanmarInfiniteScrolling.customAppend != ''){
                let cs = document.querySelector(window._tanmarInfiniteScrolling.customAppend);
                if(cs !== null){
                    cs.appendChild(div);
                }
            }else{
                me._tmisListingElement.appendChild(div);
            }

            var btn = document.querySelector('.infinite-scrolling-button-more button');
            if(btn){
                btn.addEventListener('click', function(){
                    var me = this[0];
                    var targetPage = this[1];

                    if(!me._tmisIsLoading){
                        me._tmisRequestNewPage(targetPage, 'append');

                        // remove box
                        const div = document.querySelector('.infinite-scrolling-button-more');
                        div.parentNode.removeChild(div);
                    }
                }.bind([me,nextPage]));
            }
        }
    }

    /**
     * Inject the HTML of the filtered products to the page.
     *
     * @param {String} response - HTML of filtered product data.
     */
    renderResponse(response) {
        var me = this;

        if(me._tmisActive){

            // remove loading
            me._tmisIsLoading = false;
            document.querySelectorAll('.infinite-scrolling-loading').forEach(e => {
                e.parentNode.removeChild(e);
            });

            // parse response
            var responseHtml = (new DOMParser()).parseFromString(response, 'text/html');

            // get response html
            var content = responseHtml.querySelector(me._listingRowSelector);
            if(!content){
                me._tmisLog('  content is null, responseHtml:');
                me._tmisLog(responseHtml);
            }

            // get the pagination html
            me._tmisHandleResponsePagination(responseHtml);

            // check if page already loaded
            if(me._visitedPagesIndexOf(me.currentPage) < 0){

                // add the current page to history
                me._visitedPagesAdd(me.currentPage);

                //
                me._tmisLog('  currentPage = ' + me.currentPage + ' - lastPage = ' + me.lastPage + ' - visitedPages:');
                me._tmisLog(window._tanmarInfiniteScrolling.visitedPages);

                var listing = document.querySelectorAll(me._listingRowSelector);
                if(listing.length > 0){

                    var listingLast = listing[listing.length - 1];

                    me._tmisLog('  renderResponse "' + me._tmisListingOption + '"');

                    // check direction
                    switch (me._tmisListingOption) {
                        case 'append':
                            me._tmisListingAppend(listingLast, content);
                            break;
                        case 'prepend':
                            me._tmisListingPrepend(listingLast, content);
                            break;
                        default:
                        case 'override':
                            me._tmisListingOverride(listing, content);
                            break;
                    }

                    // filter update
                    this._registry.forEach((item) => {
                        if (typeof item.afterContentChange === 'function') {
                            item.afterContentChange();
                        }
                    });

                    window.PluginManager.initializePlugins();

                    if(me._triggerAfterRenderResponseEvent){
                        this.$emitter.publish('Listing/afterRenderResponse', { response });
                    }

                    // only register new oberver if we arent on the last page
                    if(me.lastPage > me.currentPage){
                        me._tmisLog('  register new oberver');
                        me._tmisObserveLastProductBox();
                    }

                    // image fix
                    me._tmisAfterContentChange(content);

                    this.$emitter.publish('TanmarInfiniteScrolling/afterRenderResponse', { response });
                }

                // check here for display block
            }else{
                me._tmisLog('  page ' + me.currentPage + ' already loaded - ' + me._tmisListingOption);
            }
        }else{
            super.renderResponse(response);
        }
    }

    _tmisListingAppend(listingLast, content){
        var me = this;

        //listingLast.parentNode.insertBefore(content, listingLast.nextSibling);
        listingLast.innerHTML += content.innerHTML;

        if(me._tmisNewPageRequestCounter >= me._tmisNewPageRequestMax){
            // dont request new page, instead show info box
            me._tmisBuildNextInfoBox();
        }
    }

    _tmisListingPrepend(listingLast, content){
        var me = this;

        //listing[0].parentNode.insertBefore(content, listing[0]);
        listingLast.innerHTML = content.innerHTML + listingLast.innerHTML;

        if(me.currentPage > 1){
            me._tmisBuildPrevInfoBox();
        }

    }

    _tmisListingOverride(listing, content){
        var me = this;

        Array.from(listing).forEach((list,i) => {
            if(i == 0){
                list.innerHTML = content.innerHTML;
            }else{
                if(list && list.parentNode){
                    list.parentNode.removeChild(list);
                }
            }
        });

        let div = document.querySelector('.infinite-scrolling-button-prev');
        if(div){
            div.parentNode.removeChild(div);
        }
        div = document.querySelector('.infinite-scrolling-button-more')
        if(div){
            div.parentNode.removeChild(div);
        }

        if(me.lastPage > me.currentPage){
            me._tmisNewPageRequestCounter = 0;
        }

        if(me._tmisNewPageRequestCounter >= me._tmisNewPageRequestMax){
            me._tmisBuildNextInfoBox();
        }

    }

    _tmisHandleResponsePagination(responseHtml){
        var me = this;

        var pagination = responseHtml.querySelector(me._paginationResponseSelector);
        if(pagination){
            if(document.querySelectorAll('.pagination-nav').length <= 0){
                document.querySelector('body').append(pagination);
            }else{
                ElementReplaceHelper.replaceElement(pagination, document.querySelectorAll(this._paginationSelector));
            }
            
            // get the current page
            me.currentPage = parseInt(pagination.querySelector('.page-item.active input').value, 10);

            // get the last page, changed maybe by filter
            me.lastPage = parseInt(pagination.querySelector('.page-item.page-last input').value, 10);
        }else{
            // remove shopware navi
            document.querySelectorAll('.pagination-nav').forEach(nav => {
                nav.innerHTML = '';
            });
            me._tmisLog('  remove pagination-nav');

            // no pagination found in ajax, means only on page
            me.currentPage = 1;
            me.lastPage = 1;
        }
    }

    /**
     *
     */
    _tmisAfterContentChange(content){
        if(content){
            var a = content.querySelectorAll('img');
            var b = content.querySelectorAll('img');
            a.forEach((img,index) => {
                img.outerHTML = b[index].outerHTML;
            });
        }
    }

    /**
     *
     */
    resetAllFilter() {
        var me = this;
        if(me._tmisActive){
            me._tmisListingOption = 'override';
            me._visitedPagesClear();
            me._tmisNewPageRequestCounter = 0;
            me._tmisIsLoading = true;
            me._tmisLog('  reset resetAllFilter');
        }
        super.resetAllFilter();
    }

    /**
     *
     */
    resetFilter(label) {
        var me = this;
        if(me._tmisActive){
            me._tmisListingOption = 'override';
            me._visitedPagesClear();
            me._tmisNewPageRequestCounter = 0;
            me._tmisIsLoading = true;
            me._tmisLog('  reset resetFilter');
        }
        super.resetFilter(label);
    }

    /**
     * remove loading class
     */
    addLoadingElementLoaderClass() {
        if(this._tmisListingOption == 'override'){
            super.addLoadingElementLoaderClass();
        }
    }

    /**
     * remove loading class
     */
    removeLoadingElementLoaderClass() {
        if(this._tmisListingOption == 'override'){
            super.removeLoadingElementLoaderClass();
        }
    }

    refreshRegistry() {
        if(this._tmisActive){
            const visibleRegistrations = this._registry.filter((entry) => document.body.contains(entry.el));

            // only init super init
            super.init();

            this._registry = visibleRegistrations;
            window.PluginManager.initializePlugins();
        }else{
            super.refreshRegistry();
        }
    }
    
    _onWindowPopstate() {
        var me = this;
        if(me._tmisActive){
            me._tmisListingOption = 'override';
            me._visitedPagesClear();
            me._tmisNewPageRequestCounter = 0;
            me._tmisIsLoading = true;
            me._tmisLog('  reset _onWindowPopstate');
        }
        super._onWindowPopstate();
    }
    
    _tmisDestroy(){
        this._tmisActive = false;
        this._tmisLog('  _tmisDestroy');
        document.querySelectorAll('.tmis-d-none').forEach(nav => {
            nav.classList.remove('tmis-d-none');
        });
        document.querySelectorAll('body').forEach(nav => {
            nav.classList.remove('is-tanmar-infinite-scrolling');
        });
    }
}
