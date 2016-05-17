var HomePageRepository = {

    state: {
        tab: ''
    },

    /**
     *
     * @returns {string}
     */
    setDefaultTab: function () {
        if (env === 'local') {
            this.state.tab = 'transactions';
        }
        else {
            this.state.tab = 'transactions';
        }
    },

    /**
     *
     * @param tab
     */
    setTab: function (tab) {
        this.state.tab = tab;
    }
};