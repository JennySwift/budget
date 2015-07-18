;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('filterDirective', filter);

    /* @inject */
    function filter() {
        return {
            restrict: 'EA',
            scope: {
                "showFilter": "=show",
                "accounts": "=accounts",
                "multiSearch": "&search"
            },
            templateUrl: 'templates/FilterTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                $scope.resetFilter = function () {
                    $scope.filter = {
                        budget: "all",
                        total: "",
                        types: [],
                        accounts: [],
                        single_date: "",
                        from_date: "",
                        to_date: "",
                        description: "",
                        merchant: "",
                        tags: [],
                        reconciled: "any",
                        offset: 0,
                        num_to_fetch: 20
                    };
                };

                $scope.resetFilter();



                $scope.$watchCollection('filter.accounts', function (newValue, oldValue) {
                    if (newValue === oldValue) {
                        return;
                    }
                    $scope.multiSearch(true);
                });
            }
        };
    }
}).call(this);

