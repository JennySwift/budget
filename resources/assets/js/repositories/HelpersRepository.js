var HelpersRepository = {

    /**
     *
     * @param response
     */
    handleResponseError: function (response) {
        $.event.trigger('response-error', [response]);
        $.event.trigger('hide-loading');
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    numberFilter: function (number, howManyDecimals) {
        if (howManyDecimals === 2) {
            var multiplyAndDivideBy = 100;
            return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
        }
    }
};