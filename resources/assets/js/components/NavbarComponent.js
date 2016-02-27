var Navbar = Vue.component('navbar', {
    template: '#navbar-template',
    data: function () {
        return {
            me: {},
            page: 'home',
            show: {}
        };
    },
    components: {},
    methods: {
        toggleFilter: function () {
            $.event.trigger('toggle-filter');
        },
    },
    props: [

    ],
    ready: function () {

    }
});
