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
                $scope.content = $(elem).find('.content');
                var $h4 = $(elem).find('h4');

                $($h4).on('click', function () {
                    $scope.toggleContent();
                });

                $scope.toggleContent = function () {
                    if ($scope.contentVisible) {
                        $scope.hideContent();
                    }
                    else {
                        $scope.showContent();
                    }
                };

                $scope.showContent = function () {
                    $scope.content.slideDown();
                    $scope.contentVisible = true;
                };

                $scope.hideContent = function () {
                    $scope.content.slideUp();
                    $scope.contentVisible = false;
                };
            }
        };
    }
}).call(this);

