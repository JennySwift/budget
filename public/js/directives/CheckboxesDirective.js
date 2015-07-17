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
                "model": "=model"
                //'url': '@url',
                //'showPopup': '=show'
            },
            templateUrl: 'templates/CheckboxesTemplate.php',
            //scope: true,
            link: function($scope, elem, attrs) {
                $scope.animateIn = attrs.animateIn || 'zoomIn';
                $scope.animateOut = attrs.animateOut || 'zoomOut';
                var $input = $(elem).find('input');
                var $icon = $(elem).find('.label-icon');

                $scope.toggleIcon = function () {
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
                    $($icon).removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                };

                $scope.showIcon = function () {
                    $($icon).css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

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

