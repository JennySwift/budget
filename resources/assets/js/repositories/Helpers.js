require('sugar');
import requests from './Requests'
import arrays from './Arrays'
import routes from './Routes'

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
        app.f7.popup.close();
    },

    isLocalEnvironment: function () {
        return process.env.NODE_ENV === "development";
    },

    toast: function (message, type) {
        var toast = app.f7.toast.create({
            text: message,
            position: 'top',
            closeTimeout: 1500,
            cssClass: 'color-theme-green'
            // icon: '<i class="f7-icons">check_round_fill</i>'
        }).open();
    },

    notify: function (error) {
        console.log(error);
        store.hideLoading();
        var message = error.response.data.error;
        var notification = app.f7.notification.create({
            icon: '<i class="fas fa-exclamation"></i>',
            title: 'Error',
            subtitle: message,
            text: 'Click me to close',
            closeOnClick: true,
        }).open();
    },

    /**
     *
     * @param object
     */
    clone: function (object) {
        return JSON.parse(JSON.stringify(object));
    },
}