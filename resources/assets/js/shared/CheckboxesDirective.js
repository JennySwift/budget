;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('checkbox', checkbox);

    /* @inject */
    function checkbox() {
        return {
            restrict: 'EA',
            scope: {
                "model": "=model",
                "id": "@id"
            },
            templateUrl: 'checkboxes-template',
            link: function($scope, elem, attrs) {
                $scope.animateIn = attrs.animateIn || 'zoomIn';
                $scope.animateOut = attrs.animateOut || 'zoomOut';
                $scope.icon = $(elem).find('.label-icon');

                $scope.toggleIcon = function () {
                    if (!$scope.model) {
                        //Input was checked and now it won't be
                        $scope.hideIcon();
                    }
                    else {
                        //Input was not checked and now it will be
                        $scope.showIcon();
                    }
                };

                $scope.hideIcon = function () {
                    $($scope.icon).removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                };

                $scope.showIcon = function () {
                    $($scope.icon).css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

                //Make the checkbox checked on page load if it should be
                if ($scope.model === true) {
                    $scope.showIcon();
                }

                $scope.$watch('model', function (newValue, oldValue) {
                    $scope.toggleIcon();
                });
            }
        };
    }
}).call(this);

