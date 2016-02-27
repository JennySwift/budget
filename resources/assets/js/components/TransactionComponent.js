var Transaction = Vue.component('transaction', {
    template: '#transaction-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {

    },
    filters: {
        /**
         *
         * @param minutes
         * @returns {*}
         */
        formatDurationFilter: function (minutes) {
            if (minutes) {
                return '';
            }

            var moment = moment.duration(minutes, 'minutes');
            var formattedDuration = moment._data.hours + ':' + moment._data.minutes;

            return formattedDuration;
        },

        /**
         *
         * @param number
         * @param howManyDecimals
         * @returns {Number}
         */
        numberFilter: function (number, howManyDecimals) {
            if (howManyDecimals === 2) {
                var multiplyAndDivideBy = 100;
                return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
            }
        }
    },
    props: [
        'transaction',
        'showStatus',
        'showDate',
        'showDescription',
        'showMerchant',
        'showTotal',
        'showType',
        'showAccount',
        'showDuration',
        'showReconciled',
        'showAllocated',
        'showBudgets',
        'showDelete',
    ],
    ready: function () {

    }
});
