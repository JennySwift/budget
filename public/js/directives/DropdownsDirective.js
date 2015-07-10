;(function(){
    'use strict';
    angular
        .module('budgetApp')
        .directive('dropdown', dropdown);

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
            //templateUrl: 'js/directives/DropdownsTemplate.php',
            link: function($scope, elem, attrs) {

                //$scope.currentIndex = 1;
                //$scope.showPopup = true;

                var $content = $(elem).find('.dropdown-content');

                //$($content).hide();

                $scope.showDropdown = function () {
                    var $animateIn = attrs.animateIn;
                    var $animateOut = attrs.animateOut;
                    if ($($content).hasClass($animateIn)) {
                        $($content).removeClass($animateIn)
                            .addClass($animateOut);
                    }
                    else {
                        $($content).css('display', 'flex')
                            .removeClass($animateOut)
                            .addClass($animateIn);
                    }
                    //$($content).toggleClass('bounceIn')
                    //    .toggleClass('bounceOut');
                };

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

