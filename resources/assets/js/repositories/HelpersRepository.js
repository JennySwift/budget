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
     */
    scrollbars: function () {
        [].forEach.call(document.querySelectorAll('.scrollbar-container'), function (el) {
            Ps.initialize(el);
        });
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    numberFilter: function (number, howManyDecimals) {
        return accounting.formatMoney(number, {
            format: "%v"
        });
        //if (howManyDecimals === 2) {
        //    var multiplyAndDivideBy = 100;
        //
        //    //If number has only one decimal place, and a zero to the end
        //
        //    //If number has no decimal places, add two zeros to the end
        //
        //
        //    return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
        //}
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
     * @param date
     * @returns {*|String}
     */
    formatDateForUser: function (date, format) {
        return moment(date, 'YYYY-MM-DD').format(format);
    },

    /**
     *
     * @param duration
     * @returns {*}
     */
    formatDurationToMinutes: function (duration) {
        return moment.duration(duration).asMinutes();
    },

    /**
     *
     * @param minutes
     * @returns {*}
     */
    formatDurationToHoursAndMinutes: function (minutes) {
        if (!minutes && minutes != 0) {
            return '-';
        }

        var hours = Math.floor(minutes / 60);
        if (hours < 10) {
            hours = '0' + hours;
        }

        minutes = minutes % 60;
        if (minutes < 10) {
            minutes = '0' + minutes;
        }

        return hours + ':' + minutes;
    },

};