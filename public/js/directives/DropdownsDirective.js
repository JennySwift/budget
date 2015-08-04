;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('dropdownsDirective', dropdown);

    /* @inject */
    function dropdown($parse, $http) {
        return {
            restrict: 'EA',
            //scope: {
            //    //"id": "@id",
            //    //"selectedObject": "=selectedobject",
            //    'url': '@url',
            //    'showPopup': '=show'
            //},
            //templateUrl: 'templates/DropdownsTemplate.php',
            scope: true,
            link: function($scope, elem, attrs) {
                $scope.animateIn = attrs.animateIn || 'flipInX';
                $scope.animateOut = attrs.animateOut || 'flipOutX';
                var $content = $(elem).find('.dropdown-content');

                $scope.toggleDropdown = function () {
                    if ($($content).hasClass($scope.animateIn)) {
                        $scope.hideDropdown();
                    }
                    else {
                        $scope.showDropdown();
                    }
                };

                //Todo: Why is this click firing twice?
                $("body").on('click', function (event) {
                    if (!elem[0].contains(event.target)) {
                        $scope.hideDropdown();
                    }
                });

                $scope.showDropdown = function () {
                    $($content)
                        .css('display', 'flex')
                        .removeClass($scope.animateOut)
                        .addClass($scope.animateIn);
                };

                $scope.hideDropdown = function () {
                    $($content)
                        .removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                        //.css('display', 'none');
                };
            }
        };
    }
}).call(this);

