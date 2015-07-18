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
                //"id": "@id",
                "model": "=model",
                //"element": "@element",
                //"model": "@model"
            },
            //scope: true,
            templateUrl: 'templates/CheckboxesTemplate.php',
            link: function($scope, elem, attrs) {
                //$scope.model = attrs.model;
                $scope.animateIn = attrs.animateIn || 'zoomIn';
                $scope.animateOut = attrs.animateOut || 'zoomOut';

                $scope.toggleIcon = function () {
                    var $input = $(elem).find('input');
                    var $icon = $(elem).find('.label-icon');
                    if ($($input).is(':checked')) {
                        //Input was checked and now it won't be
                        $scope.hideIcon();
                    }
                    else {
                        //Input was not checked and now it will be
                        $scope.showIcon();
                    }
                };

                $scope.hideIcon = function () {
                    $scope.model = false;
                    var $input = $(elem).find('input');
                    var $icon = $(elem).find('.label-icon');
                    $($icon).removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                };

                $scope.showIcon = function () {
                    $scope.model = true;
                    var $input = $(elem).find('input');
                    var $icon = $(elem).find('.label-icon');
                    $($icon).css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

                //Make the checkbox checked on page load if it should be
                if ($scope.model === true) {
                    $scope.showIcon();
                }

                //$scope.$watch('model', function (newValue, oldValue) {
                //    $scope.toggleIcon();
                //    console.log(elem);
                //});


                //$scope.showDropdown = function () {
                //    if ($($content).hasClass($scope.animateIn)) {
                //        $scope.hideDropdown();
                //    }
                //    else {
                //        $($content).css('display', 'flex')
                //            .removeClass($scope.animateOut)
                //            .addClass($scope.animateIn);
                //    }
                //};
            }
        };
    }
}).call(this);

