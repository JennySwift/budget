require('sugar');
import requests from './Requests'
import arrays from './Arrays'
import routes from './Routes'
import dateandtime from './DateTimeRepository'
import accounting from 'accounting'

export default {

    //Request methods
    requests: requests,
    get: requests.get,
    post: requests.post,
    put: requests.put,
    delete: requests.delete,

    //Array methods
    findById: arrays.findById,
    findIndexById: arrays.findIndexById,
    deleteById: arrays.deleteById,
    deleteFromArray: arrays.deleteFromArray,
    updateItemInArray: arrays.updateItemInArray,

    //Route methods
    getIdFromRouteParams: routes.getIdFromRouteParams,
    goToRoute: routes.goToRoute,
    getRouteName: routes.getRouteName,
    getCurrentPath: routes.getCurrentPath,
    isHomePage: routes.isHomePage,
    getRouteHistory: routes.getRouteHistory,
    getRoutePath: routes.getRoutePath,
    getRouter: routes.getRouter,

    //Date and time methods
    convertToDateTime: dateandtime.convertToDateTime,
    convertToMySqlDate: dateandtime.convertToMySqlDate,
    formatDurationToHoursAndMinutes: dateandtime.formatDurationToHoursAndMinutes,
    formatDurationToMinutes: dateandtime.formatDurationToMinutes,
    formatDateForUser: dateandtime.formatDateForUser,


    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        return 0;
    },

    getScreenWidth: function () {
        return screen.width;
    },

    convertIntegerToBoolean: function (boolean) {
        if (boolean) {
            return true;
        }
        return false;
    },

    hidePopup: function () {
        // app.f7.popup.close();
        store.set(false, 'show.popup.' + popupName);
    },

    showPopup: function (popupName) {
        store.set(true, 'show.popup.' + popupName);
    },

    /**
     *
     */
    closePopup: function ($event, that, routeToGoTo) {
        if ($($event.target).hasClass('popup-outer')) {
            // that.$emit('update:showPopup', false);
            // that.$router.push(routeToGoTo);
            store.set(false, 'show.popup.' + that.popupName);
        }
    },


    isLocalEnvironment: function () {
        return process.env.NODE_ENV === "development";
    },

    toast: function (message, type) {
        // var toast = app.f7.toast.create({
        //     text: message,
        //     position: 'top',
        //     closeTimeout: 1500,
        //     cssClass: 'color-theme-green'
        //     // icon: '<i class="f7-icons">check_round_fill</i>'
        // }).open();
    },

    notify: function (error) {
        // store.hideLoading();
        // var message = error.response.data.error;
        // var notification = app.f7.notification.create({
        //     icon: '<i class="fas fa-exclamation"></i>',
        //     title: 'Error',
        //     subtitle: message,
        //     text: 'Click me to close',
        //     closeOnClick: true,
        // }).open();
    },

    /**
     *
     * @param object
     */
    clone: function (object) {
        return JSON.parse(JSON.stringify(object));
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
     * @param number
     * @returns {*}
     */
    addZeros: function (number) {
        if (number < 10) {
            return '0' + number;
        }

        return number;
    },

    /**
     *
     * @param number
     * @param howManyDecimals
     * @returns {number}
     */
    roundNumber: function (number, howManyDecimals) {
        if (!howManyDecimals) {
            return Math.round(number);
        }

        var multiplyAndDivideBy = Math.pow(10, howManyDecimals);
        return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
    },

    /**
     * commenting out for now because it was erroring saying .tooltipster is not a function
     */
    tooltips: function () {
        var width = $(window).width();
        // Trigger on click rather than hover for small screens
        var trigger = width < 800 ? 'click' : 'hover';

        $('.tooltipster').tooltipster({
            theme: 'tooltipster-punk',
            //Animation duration for in and out
            animationDuration: [1000, 500],
            trigger: trigger,
            side: 'right',
            functionInit: function(instance, helper){

                var $origin = $(helper.origin),
                    dataOptions = $origin.attr('data-tooltipster');

                if(dataOptions){

                    dataOptions = JSON.parse(dataOptions);

                    $.each(dataOptions, function(name, option){
                        instance.option(name, option);
                    });
                }
            }
        });
    },



}