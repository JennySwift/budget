require('sweetalert2');

require('jquery');
require('tooltipster');
import requests from './Requests'
import arrays from './Arrays'
import dateandtime from './DateTimeRepository'
import store from './Store'
import accounting from 'accounting'

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

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

    //Date and time methods
    convertToDateTime: dateandtime.convertToDateTime,
    convertToMySqlDate: dateandtime.convertToMySqlDate,
    formatDurationToHoursAndMinutes: dateandtime.formatDurationToHoursAndMinutes,
    formatDurationToMinutes: dateandtime.formatDurationToMinutes,
    formatDateForUser: dateandtime.formatDateForUser,

    /**
     *
     * @param data
     * @param status
     * @param response
     */
    handleResponseError: function (response) {
        store.hideLoading();
        app.__vue__.$bus.$emit('response-error', response);
        // $.event.trigger('response-error', [data, status, response]);
        $.event.trigger('hide-loading');
    },

    /**
     *
     * @param messages
     * @param type
     */
    provideFeedback: function (messages, type) {
        app.__vue__.$bus.$emit('provide-feedback', messages, type);
    },

    getRouter: function () {
        if (app.__vue__) {
            return app.__vue__.$router;
        }
    },

    getRoutePath: function () {
        var router = this.getRouter();
        if (router) {
            return router.currentRoute.path;
        }
    },

    goToRoute (path) {
        this.getRouter().push(path);
    },

    /**
     * for vue-js-modal
     * @param popupName
     */
    // hidePopup: function (popupName) {
    //     app.__vue__.$modal.hide(popupName);
    // },

    // openPopup: function (popupName) {
    //     app.__vue__.$modal.show(popupName);
    // },

    showPopup: function (popupName) {
        store.set(true, 'show.popup.' + popupName);
    },

    hidePopup: function () {
        store.set(false, 'showPopup');
    },

    /**
     *
     */
    closePopup: function ($event, that, routeToGoTo) {
        if ($($event.target).hasClass('popup-outer')) {
            // that.$emit('update:showPopup', false);
            // that.$router.push(routeToGoTo);
            store.set(false, 'showPopup');
        }
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
     * @param boolean
     * @returns {number}
     */
    convertBooleanToInteger: function (boolean) {
        if (boolean) {
            return 1;
        }
        return 0;
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


    getCurrentPath () {
        return this.getRouter().currentRoute.path;
    },

    /**
     * If url is /items/:2, return 2
     * @param that
     * @returns {*}
     */
    getIdFromUrl: function () {
        var idWithColon =  this.getRouter().currentRoute.params.id;
        var id;

        if (!idWithColon) return false;

        var index = idWithColon.indexOf(':');

        if (index != -1) {
            id = idWithColon.slice(index+1);
        }

        return id;
    },
    
    /**
     *
     */
    scrollbars: function () {
        var containers = $('.scrollbar-container');
        $(containers).each(function () {
            var container = $(this);
            var height = container.css('height');
            var maxHeight = container.css('max-height');

            if (height === '0px') {
                height = 0;
            }
            if (maxHeight === 'none') {
                maxHeight = 0;
            }

            if (!height && !maxHeight) {
                container.height('100%');
            }
            if (container.css('position') == 'static') {
                container.css({position: 'relative'});
            }
            // container.perfectScrollbar();
            container.mCustomScrollbar({
                theme: 'minimal-dark'
            });
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
    }
}