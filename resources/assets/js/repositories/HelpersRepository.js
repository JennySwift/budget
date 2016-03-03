var HelpersRepository = {

    /**
     *
     * @param data
     * @param status
     */
    handleResponseError: function (data, status, response) {
        $.event.trigger('response-error', [data, status, response]);
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
    },

    /**
     *
     */
    closePopup: function ($event, that) {
        if ($event.target.className === 'popup-outer') {
            that.showPopup = false;
        }
    },

    /**
     *
     * @param boolean
     * @returns {*}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        else {
            return 0;
        }
    },

    /**
     *
     * @param date
     * @returns {*}
     */
    formatDate: function (date) {
        if (date) {
            if (!Date.parse(date)) {
                $.event.trigger('provide-feedback', ['Date is invalid', 'error']);
                return date;
            } else {
                return Date.parse(date).toString('yyyy-MM-dd');
            }
        }
    },

    /**
     *
     * @param duration
     * @returns {*}
     */
    formatDurationToMinutes: function (duration) {
        return moment.duration(duration).asMinutes();
    }
};