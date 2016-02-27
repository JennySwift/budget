
var App = Vue.component('app', {

});

var router = new VueRouter({
    hashbang: false
});

router.map({
    '/': {
        component: HomePage,
        //subRoutes: {
        //    //default for if no id is specified
        //    '/': {
        //        component: Item
        //    },
        //    '/:id': {
        //        component: Item
        //    }
        //}
    },
    '/help': {
        component: HelpPage
    },
    '/acounts': {
        component: AccountsPage
    }
});

router.start(App, 'body');

//$rootScope.show = ShowFactory.defaults;

$(window).load(function () {
    $(".main").css('display', 'block');
    $("footer, #navbar").css('display', 'flex');
    $("#page-loading").hide();
    //$rootScope.$emit('getSideBarTotals');
});

//$rootScope.deleteUser = function () {
//    if (confirm("Do you really want to delete your account?")) {
//        if (confirm("You are about to delete your account! You will no longer be able to use the budget app. Are you sure this is what you want?")) {
//            $rootScope.showLoading();
//            UsersFactory.deleteAccount(me)
//                .then(function (response) {
//                    $rootScope.$broadcast('provideFeedback', 'Your account has been deleted');
//                    $rootScope.hideLoading();
//                })
//                .catch(function (response) {
//                    $rootScope.responseError(response);
//                });
//        }
//    }
//};



