<template>
    <ul id="navbar" style="z-index:1000">

        <li><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

        @if (Auth::guest())
        <li>
            <a href="/login">Login</a>
        </li>
        <li>
            <a href="/register">Register</a>
        </li>

        @else
        <li>
            <a
                v-link="{path: '/'}"
                class="fa fa-home"
            >
            </a>
        </li>

        <li>
            <a
                v-link="{path: '/graphs'}"
            >
                Graphs
            </a>
        </li>

        @include('templates.header.menu')

        @include('templates.header.show')

        @include('templates.header.user')

        @include('templates.header.budgets')

        @include('templates.header.help')

        <li>
            <a v-on:click="toggleFilter()" class="fa fa-search"></a>
        </li>

        @endif

    </ul>
</template>

<script>
    export default {
        data: function () {
            return {
                me: me,
                page: 'home',
            };
        },
        components: {},
        methods: {
            toggleFilter: function () {
                $.event.trigger('toggle-filter');
            },

            /**
             *
             */
            showAllTransactionProperties: function () {
                this.transactionPropertiesToShow = ShowRepository.setTransactionDefaults();
            },

            /**
             *
             * @param property
             */
            toggleTransactionProperty: function (property) {
                this.transactionPropertiesToShow[property] = !this.transactionPropertiesToShow[property];
                this.transactionPropertiesToShow.all = this.calculateIfAllTransactionPropertiesAreShown();
            },

            /**
             *
             * @returns {*}
             */
            calculateIfAllTransactionPropertiesAreShown: function () {
                var that = this;
                var allShown = true;
                $.each(this.transactionPropertiesToShow, function (key, value) {
                    if (key !== 'all' && !value) {
                        allShown = false;
                    }
                });

                return allShown;

                //var hiddenProperties = _.filter(that.transactionPropertiesToShow, function (property) {
                //    return property == false;
                //});
                //
                //if (hiddenProperties.length > 0) {
                //    return false;
                //}
                //
                //return true;
            }
        },
        props: [
            'show',
            'transactionPropertiesToShow'
        ],
        mounted: function () {

        }
    }
</script>