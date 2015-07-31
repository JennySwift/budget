;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('filterDropdownsDirective', filterDropdown);

    /* @inject */
    function filterDropdown($parse, $http) {
        return {
            restrict: 'A',
            //scope: {
            //    //"model": "=model",
            //    //"id": "@id"
            //    "types": "=types",
            //    "path": "@path"
            //},
            //templateUrl: 'filter-dropdowns',
            scope: true,
            link: function($scope, elem, attrs) {
                //$scope.showContent = true;
                $scope.content = $(elem).find('.content');

                $scope.showContent = function (event) {
                    $scope.content.slideDown();

                };

                $scope.hideContent = function (event) {
                    $scope.content.slideUp();
                };
            }
        };
    }
}).call(this);

