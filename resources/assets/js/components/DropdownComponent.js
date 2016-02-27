var Dropdown = Vue.component('dropdown', {
    template: '#dropdown-template',
    data: function () {
        return {
            animateIn: attrs.animateIn || 'flipInX',
            animateOut: attrs.animateOut || 'flipOutX',
            content: $(elem).find('.dropdown-content')
        };
    },
    components: {},
    methods: {
        toggleDropdown: function () {
            if ($($content).hasClass($scope.animateIn)) {
                $scope.hideDropdown();
            }
            else {
                $scope.showDropdown();
            }
        },

        showDropdown: function () {
            $($content)
                .css('display', 'flex')
                .removeClass($scope.animateOut)
                .addClass($scope.animateIn);
        },

        hideDropdown: function () {
            $($content)
                .removeClass($scope.animateIn)
                .addClass($scope.animateOut);
            //.css('display', 'none');
        },

        listen: function () {
            //Todo: Why is this click firing twice?
            $("body").on('click', function (event) {
                if (!elem[0].contains(event.target)) {
                    $scope.hideDropdown();
                }
            });
        },

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});





