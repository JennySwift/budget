var app = angular.module('budgetApp', ['checklist-model', 'ngAnimate'], function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

(function () {

    angular
        .module('budgetApp')
        .controller('BaseController', base);

    function base ($scope, $http, FeedbackFactory) {
        /**
         * Scope properties
         */
        $scope.feedback_messages = [];
        $scope.show = {
            popups: {}
        };
        $scope.me = me;

        $(window).load(function () {
            $(".main").css('display', 'block');
            $("#budget").css('display', 'flex');
            $("footer, #navbar").css('display', 'flex');
            $("#page-loading").hide();
        });

        $scope.showLoading = function () {
            $scope.loading = true;
        };

        $scope.hideLoading = function () {
            $scope.loading = false;
        };

        $scope.provideFeedback = function ($message) {
            $scope.feedback_messages.push($message);
            setTimeout(function () {
                $scope.feedback_messages = _.without($scope.feedback_messages, $message);
                $scope.$apply();
            }, 3000);
        };

        $scope.responseError = function (response) {
            if (response.status === 503) {
                FeedbackFactory.provideFeedback('Sorry, application under construction. Please try again later.');
            }
            else {
                FeedbackFactory.provideFeedback('There was an error');
            }
            $scope.hideLoading();
        };

        $scope.closePopup = function ($event, $popup) {
            var $target = $event.target;
            if ($target.className === 'popup-outer') {
                $scope.show.popups[$popup] = false;
            }
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