(function () {

    angular
        .module('budgetApp')
        .controller('HomeController', home);

    function home ($scope, TransactionsFactory, PreferencesFactory) {

        $scope.transactionsFactory = TransactionsFactory;
        $scope.page = 'home';

        $scope.colors = me.preferences.colors;

        if ($scope.env === 'local') {
            $scope.tab = 'transactions';
        }
        else {
            $scope.tab = 'transactions';
        }

        $scope.$watch('PreferencesFactory.date_format', function (newValue, oldValue) {
            if (!newValue) {
                return;
            }
            $scope.showLoading();
            PreferencesFactory.insertOrUpdateDateFormat(newValue)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        });

        $scope.toggleFilter = function () {
            $scope.show.filter = !$scope.show.filter;
        };

        $scope.transactionsTab = function () {
            $scope.tab = 'transactions';
            $scope.show.basic_totals = true;
            $scope.show.budget_totals = true;
            $scope.show.filter = false;
        };

        $scope.graphsTab = function () {
            $scope.tab = 'graphs';
            $scope.show.basic_totals = false;
            $scope.show.budget_totals = false;
            $scope.show.filter = true;
        };

        if ($scope.tab === 'graphs') {
            $scope.graphsTab();
        }










        /*==============================quick select of transactions==============================*/

        $("body").on('click', '.mass-delete-checkbox-container', function (event) {
            var $this = $(this).closest("tbody");
            var $checked = $(".checked");
            $(".last-checked").removeClass("last-checked");
            $(".first-checked").removeClass("first-checked");

            if (event.shiftKey) {
                var $last_checked = $($checked).last().closest("tbody");
                var $first_checked = $($checked).first().closest("tbody");

                $($last_checked).addClass("last-checked");
                $($first_checked).addClass("first-checked");
                $($this).addClass("checked");

                if ($($this).prevAll(".last-checked").length !== 0) {
                    //$this is after .last-checked
                    shiftSelect("forwards");
                }
                else if ($($this).nextAll(".last-checked").length !== 0) {
                    //$this is before .last-checked
                    shiftSelect("backwards");
                }
            }
            else if (event.altKey) {
                $($this).toggleClass('checked');
            }
            else {
                console.log("no shift");
                $(".checked").not($this).removeClass('checked');
                $($this).toggleClass('checked');
            }
        });

        function shiftSelect ($direction) {
            $("#my_results tbody").each(function () {
                var $prev_checked_length = $(this).prevAll(".checked").length;
                var $after_checked_length = $(this).nextAll(".checked").length;
                var $after_last_checked = $(this).prevAll(".last-checked").length;
                var $before_first_checked = $(this).nextAll(".first-checked").length;

                if ($direction === "forwards") {
                    //if it's after $last_checked and before $this
                    if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $after_last_checked !== 0) {
                        $(this).addClass('checked');
                    }
                }
                else if ($direction === "backwards") {
                    if ($prev_checked_length !== 0 && $after_checked_length !== 0 && $before_first_checked !== 0) {
                        $(this).addClass('checked');
                    }
                }
            });
        }

    }

})();