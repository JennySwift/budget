(function () {

    angular
        .module('budgetApp')
        .controller('TransactionsController', transactions);

    function transactions ($scope, $http, TransactionsFactory) {
        $scope.transactionsFactory = TransactionsFactory;

        $scope.$watch('transactionsFactory.testControllers()', function (newValue, oldValue, scope) {
            scope.num = newValue;
        });
    }

})();