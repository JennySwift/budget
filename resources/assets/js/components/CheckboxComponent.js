var Checkbox = Vue.component('checkbox', {
    template: '#checkbox-template',
    data: function () {
        return {
            animateIn: attrs.animateIn || 'zoomIn',
            animateOut: attrs.animateOut || 'zoomOut',
            icon: $(elem).find('.label-icon'),
        };
    },
    components: {},
    methods: {
        toggleIcon: function () {
            if (!$scope.model) {
                //Input was checked and now it won't be
                $scope.hideIcon();
            }
            else {
                //Input was not checked and now it will be
                $scope.showIcon();
            }
        },

        hideIcon: function () {
            $($scope.icon).removeClass($scope.animateIn)
                .addClass($scope.animateOut);
        },

        showIcon: function () {
            $($scope.icon).css('display', 'flex')
                .removeClass($scope.animateOut)
                .addClass($scope.animateIn);
        },

    },
    props: [
        'model',
        'id'
    ],
    ready: function () {

    }
});

////Make the checkbox checked on page load if it should be
//if ($scope.model === true) {
//    $scope.showIcon();
//}
//
//$scope.$watch('model', function (newValue, oldValue) {
//    $scope.toggleIcon();
//});
