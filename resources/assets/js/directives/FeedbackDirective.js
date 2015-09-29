angular.module('budgetApp')
    .directive('feedbackDirective', function ($sce, $timeout) {
        return {
            scope: {},
            templateUrl: '/feedback',

            link: function ($scope) {
                $scope.feedbackMessages = [];
                $scope.$on('provideFeedback', function (event, message, type) {
                    var newMessage = {
                        message: $sce.trustAsHtml(message),
                        type: type
                    };

                    $scope.feedbackMessages.push(newMessage);

                    $timeout(function () {
                        $scope.feedbackMessages = _.without($scope.feedbackMessages, newMessage);
                    }, 3000);
                });
            }
        }
    });

