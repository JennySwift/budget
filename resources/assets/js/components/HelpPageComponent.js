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
            //var scrollTop = $("body").height();
            var element = $('#' + id);
            var scrollTop = element.offset().top;
            //var element = document.getElementById(id);
            console.log(scrollTop);

            //var scrollTop = $('#' + id).scrollTop();
            //$('html,body').animate({scrollTop: scrollTop - 1}, 1);
            setTimeout(function () {
                $('html,body').animate({scrollTop: scrollTop}, 700);
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
