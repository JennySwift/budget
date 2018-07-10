export default {
    getIdFromRouteParams: function (that) {
        var id;
        if (that) {
            id = that.$f7route.params.id;
        }
        else {
            id = app.f7.view.main.router.currentRoute.params.id;
        }

        if (!id) {
            return false;
        }
        return id.replace(':', '');
    },

    getCurrentUrl: function () {
        return app.f7.view.main.router.url;
    },

    getCurrentPath: function () {
        return app.f7.view.main.router.currentRoute.path;
    },

    isHomePage: function () {
        return !app.f7.views.main.router.url;
    },

    goToRoute: function (path) {
        app.f7.router.navigate(path, {
            history: true,
            reloadAll: false,
            clearPreviousHistory: false,
            pushState: true
        });
        console.log(this.getRouteHistory());
    },

    getRouteHistory: function () {
        return app.f7.view.main.router.history;
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
    }

}
