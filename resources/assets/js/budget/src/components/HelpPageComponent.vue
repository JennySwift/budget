<template>
    <div>

        @include('main.help.help-navigation')

        <h1>Help</h1>

        <div id="help">
            @include('main.help.help-content')
        </div>
    </div>
</template>

<script>
    export default {
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
                var element = $('#' + id);
                var scrollTop = element.offset().top;
                setTimeout(function () {
                    $('html,body').animate({scrollTop: scrollTop}, 700);
                }, 100);
            }
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            HelpersRepository.scrollbars();
        }
    }
</script>