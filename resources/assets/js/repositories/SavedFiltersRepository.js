var SavedFiltersRepository = {

    state: {
        savedFilters: []
    },

    /**
     *
     */
    getSavedFilters: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/savedFilters', function (response) {
            SavedFiltersRepository.state.savedFilters = response;
            $.event.trigger('hide-loading');
        })
        .error(function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     * 
     * @param savedFilter
     */
    addSavedFilter: function (savedFilter) {
        this.state.savedFilters.push(savedFilter);
    },

    /**
    *
    * @param savedFilter
    */
    deleteSavedFilter: function (savedFilter) {
        var index = HelpersRepository.findIndexById(this.state.savedFilters, savedFilter.id);
        this.state.savedFilters = _.without(this.state.savedFilters, this.state.savedFilters[index]);
    }

};