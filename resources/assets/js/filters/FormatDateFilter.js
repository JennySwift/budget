angular.module('budgetApp')
    .filter('formatDate', function ($rootScope) {
        return function (input) {
            if (input) {
                if (!Date.parse(input)) {
                    //$rootScope.$broadcast('provideFeedback', {message: 'Date is invalid', type:'error'});
                    return input;
                } else {
                    return Date.parse(input).toString('yyyy-MM-dd');
                }
            }
        }
    });

