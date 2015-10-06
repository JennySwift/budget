angular.module('budgetApp')
    .directive('formattedDate', function ($filter) {
        return {
            restrict: 'A',
            require: '?ngModel',
            link: function (scope, element, attrs, ngModel) {

                //element.on('keyup', function (event) {
                //    if (Date.parse(ngModel.$viewValue)) {
                //        ngModel.$modelValue = $filter('formatDate')(ngModel.$viewValue);
                //
                //    }
                //});

            }
        };
    });

