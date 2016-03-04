var Dropdown = Vue.component('dropdown', {
    //template: '#dropdown-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
        toggleDropdown: function () {
            if ($(this.$el).find('.dropdown-content').hasClass(this.animateInClass)) {
                this.hideDropdown();
            }
            else {
                this.showDropdown();
            }
        },

        showDropdown: function () {
            $(this.$el).find('.dropdown-content')
                .css('display', 'flex')
                .removeClass(this.animateOutClass)
                .addClass(this.animateInClass);
        },

        hideDropdown: function () {
            $(this.$el).find('.dropdown-content')
                .removeClass(this.animateInClass)
                .addClass(this.animateOutClass);
            //.css('display', 'none');
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $("body").on('click', function (event) {
                if (!that.$el || !that.$el.contains(event.target)) {
                    that.hideDropdown();
                }
            });
        },

    },
    props: [
        'animateInClass',
        'animateOutClass'
    ],
    ready: function () {
        this.listen();
    }
});





