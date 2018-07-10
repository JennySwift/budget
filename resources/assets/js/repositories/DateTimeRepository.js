require('sugar');
var moment = require('moment');
export default {
    /**
     *
     * @param dateAndTime
     * @returns {*}
     */
    convertToDateTime: function (dateAndTime) {
        if (!dateAndTime || dateAndTime === '') {
            return null;
        }

        var dateTime = Date.create(dateAndTime).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');

        if (dateTime == 'Invalid Date') {
            //Only add my shortcuts if the date is invalid for Sugar
            if (dateAndTime == 't') {
                dateAndTime = 'today';
            }
            else if (dateAndTime == 'to') {
                dateAndTime = 'tomorrow';
            }
            else if (dateAndTime == 'y') {
                dateAndTime = 'yesterday';
            }

            dateTime = Date.create(dateAndTime).format('{yyyy}-{MM}-{dd} {HH}:{mm}:{ss}');
        }

        return dateTime;
    },

    /**
     *
     * @param dateTime
     * @param format - format to convert to
     * @returns {*}
     */
    convertFromDateTime: function (dateTime, format) {
        format = format || 'ddd DD MMM YYYY';
        return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format(format);
    },

    /**
     *
     * @param date
     * @returns {*}
     */
    convertToMySqlDate: function (date) {
        if (date) {
            if (!Date.create(date)) {
                $.event.trigger('provide-feedback', ['Date is invalid', 'error']);
                return date;
            } else {
                return Date.create(date).format('{yyyy}-{MM}-{dd}');
            }
        }
    },

    /**
     *
     * @param date
     * @returns {*|String}
     */
    formatDateForUser: function (date) {
        return moment(date, 'YYYY-MM-DD').format(store.state.me.preferences.dateFormat);
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


}