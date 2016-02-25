var DropdownForFilter = Vue.component('dropdown-for-filter', {
    template: '#dropdown-for-filter-template',
    data: function () {
        return {
            content: $(elem).find('.content')
        };
    },
    components: {},
    methods: {
        toggleContent: function () {
            if ($scope.contentVisible) {
                $scope.hideContent();
            }
            else {
                $scope.showContent();
            }
        },

        showContent: function () {
            $scope.content.slideDown();
            $scope.contentVisible = true;
        },

        hideContent: function () {
            $scope.content.slideUp();
            $scope.contentVisible = false;
        },

        listen: function () {
            var $h4 = $(elem).find('h4');

            $($h4).on('click', function () {
                $scope.toggleContent();
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});

