angular.module('budgetApp')
    .directive('formattedDurationDirective', function ($filter) {
        return {
            restrict: 'A',
            require: '?ngModel',
            link: function (scope, element, attrs, ngModel) {

                function formatDuration(input) {
                    return $filter('formatDurationToMinutesFilter')(input);
                }
                ngModel.$parsers.push(formatDuration);

            }
        };

    });



