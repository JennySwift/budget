var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

(function () {

    angular
        .module('budgetApp')
        .controller('BaseController', base);

    function base ($scope, $http, $sce, TotalsFactory, UsersFactory, FilterFactory) {
        /**
         * Scope properties
         */
        $scope.feedback_messages = [];
        $scope.show = {
            popups: {}
        };
        $scope.me = me;

        if (typeof env !== 'undefined') {
            $scope.env = env;
        }

        if (typeof basicTotals !== 'undefined') {
            $scope.basicTotals = basicTotals;
            $scope.fixedBudgetTotals = fixedBudgetTotals;
            $scope.flexBudgetTotals = flexBudgetTotals;
            $scope.remainingBalance = remainingBalance;
        }

        if (page === 'home') {
            //Putting this here so that transactions update
            //after inserting transaction from newTransactionController
            $scope.transactions = filter_response.transactions;

            $scope.filter = FilterFactory.filter;

            $scope.filterTransactions = function () {
                $scope.showLoading();
                FilterFactory.filterTransactions($scope.filter)
                    .then(function (response) {
                        $scope.hideLoading();
                        $scope.transactions = response.data.transactions;
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    })
            };
        }

        $scope.totalChanges = {};

        $scope.clearTotalChanges = function () {
            $scope.totalChanges = {};
        };

        $scope.getTotals = function () {
            $scope.showLoading();
            TotalsFactory.getTotals()
                .then(function (response) {
                    $scope.updateTotalsAfterResponse(response);
                    //$scope.provideFeedback('');
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        };

        $scope.updateTotalsAfterResponse = function (response) {
            $scope.basicTotals = response.data.basicTotals;
            $scope.fixedBudgetTotals = response.data.fixedBudgetTotals;
            $scope.flexBudgetTotals = response.data.flexBudgetTotals;
            $scope.remainingBalance = response.data.remainingBalance;
        };

        $(window).load(function () {
            $(".main").css('display', 'block');
            //$("#budget").css('display', 'flex');
            $("footer, #navbar").css('display', 'flex');
            $("#page-loading").hide();
        });

        $scope.showLoading = function () {
            $scope.loading = true;
        };

        $scope.hideLoading = function () {
            $scope.loading = false;
        };

        $scope.provideFeedback = function ($message, $type) {
            var $new = {
                message: $sce.trustAsHtml($message),
                type: $type
            };

            $scope.feedback_messages.push($new);

            //$scope.feedback_messages.push($message);

            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $new);
                $scope.$apply();
            }, 3000);
        };

        $scope.responseError = function (response) {
            if(typeof response !== "undefined") {
                switch(response.status) {
                    case 503:
                        $scope.provideFeedback('Sorry, application under construction. Please try again later.', 'error');
                        break;
                    case 401:
                        $scope.provideFeedback('You are not logged in', 'error');
                        break;
                    case 422:
                        var html = "<ul>";
                        angular.forEach(response.data, function(value, key) {
                            var fieldName = key;
                            angular.forEach(value, function(value) {
                                html += '<li>'+value+'</li>';
                            });
                        });
                        html += "</ul>";
                        $scope.provideFeedback(html, 'error');
                        break;
                    default:
                        $scope.provideFeedback(response.data.error, 'error');
                        break;
                }
            }
            else {
                $scope.provideFeedback('There was an error', 'error');
            }
            //if (response.status === 503) {
            //    $scope.provideFeedback('Sorry, application under construction. Please try again later.', 'error');
            //}
            //else if (response.status === 401) {
            //    $scope.provideFeedback('You are not logged in', 'error');
            //}
            //// Validation errors
            //else if (response.status === 422) {
            //    var html = "<ul>";
            //    angular.forEach(response.data, function(value, key) {
            //        var fieldName = key;
            //        angular.forEach(value, function(value) {
            //            html += '<li>'+value+'</li>';
            //        });
            //    });
            //    html += "</ul>";
            //    $scope.provideFeedback(html, 'error');
            //}
            //else if (response.data.error) {
            //    $scope.provideFeedback(response.data.error, 'error');
            //}
            //else if (response.data) {
            //    //Todo (response.data is in a complicated format)
            //
            //}
            //else {
            //    $scope.provideFeedback('There was an error', 'error');
            //}
            $scope.hideLoading();
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
        };

        $scope.deleteUser = function () {
            if (confirm("Do you really want to delete your account?")) {
                if (confirm("You are about to delete your account! You will no longer be able to use the budget app. Are you sure this is what you want?")) {
                    $scope.showLoading();
                    UsersFactory.deleteAccount($scope.me)
                        .then(function (response) {
                            //$scope. = response.data;
                            $scope.provideFeedback('Your account has been deleted');
                            $scope.hideLoading();
                        })
                        .catch(function (response) {
                            $scope.responseError(response);
                        });
                }
            }
        };

        $scope.formatDate = function ($date) {
            if ($date) {
                if (!Date.parse($date)) {
                    $scope.provideFeedback('Date is invalid', 'error');
                    return false;
                }
                else {
                    return Date.parse($date).toString('yyyy-MM-dd');
                }
            }
            return false;
        };
    }

})();




/*==============================dates==============================*/

$("#convert_date_button_2").on('click', function () {
    $(this).toggleClass("long_date");
    $("#my_results .date").each(function () {
        var $date = $(this).val();
        var $parse = Date.parse($date);
        var $toString;
        if ($("#convert_date_button_2").hasClass("long_date")) {
            $toString = $parse.toString('dd MMM yyyy');
        }
        else {
            $toString = $parse.toString('dd/MM/yyyy');
        }

        $(this).val($toString);
    });
});

/*==============================new month==============================*/

function newMonth () {
    $("#fixed-budget-info-table .spent").each(function () {
        $(this).text(0);
    });
}