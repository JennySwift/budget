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

                $scope.showDropdown = function () {
                    if ($($content).hasClass($scope.animateIn)) {
                        $scope.hideDropdown();
                    }
                    else {
                        $($content).css('display', 'flex')
                            .removeClass($scope.animateOut)
                            .addClass($scope.animateIn);
                    }
                };

                //Todo: Why is this click firing twice?
                $("body").on('click', function (event) {
                    if (!elem[0].contains(event.target)) {
                        $scope.hideDropdown();
                    }
                });

                $scope.hideDropdown = function () {
                    $($content).removeClass($scope.animateIn)
                        .addClass($scope.animateOut);
                };

                //$scope.closePopup = function ($event, $popup) {
                //    var $target = $event.target;
                //    if ($target.className === 'popup-outer') {
                //        $scope.show.popups[$popup] = false;
                //    }
                //    $scope.stopJsTimer();
                //};

                /**
                 * Query the database
                 */
                //$scope.searchDatabase = function () {
                //    var $data = {
                //        typing: $scope.inputValue
                //    };
                //
                //    $http.post($scope.url, $data).
                //        success(function(response, status, headers, config) {
                //            $scope.dealWithResults(response);
                //        }).
                //        error(function(data, status, headers, config) {
                //            //todo: Can I access my provideFeedback method in my controller from here?
                //            console.log("There was an error");
                //        });
                //};
            }
        };
    }
}).call(this);

