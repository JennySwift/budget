var HelpPage = Vue.component('help-page', {
    template: '#help-page-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {


        /**
         *
         */
        scrollTo: function (id) {
            window.scrollTo(0, 500);

            //var scrollTop = $("body").height();
            var scrollTop = $(id).scrollTop();
            //$('html,body').animate({scrollTop: scrollTop - 1}, 1);
            setTimeout(function () {
                $('html,body').animate({scrollTop: 0}, 700);
            }, 100);
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        HelpersRepository.scrollbars();
    }
});
