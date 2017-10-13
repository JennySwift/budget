<template>
    <ul id="navbar" style="z-index:1000">

        <li><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

        <!--Todo: this part only if not logged in-->
        <!--<li>-->
            <!--<a href="/login">Login</a>-->
        <!--</li>-->
        <!--<li>-->
            <!--<a href="/register">Register</a>-->
        <!--</li>-->

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

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">

                <li>
                    <a v-link="{path: '/accounts'}">Accounts</a>
                </li>

                <li>
                    <a v-link="{path: '/preferences'}">Preferences</a>
                </li>

                <li>
                    <a v-link="{path: '/favourite-transactions'}">Favourite transactions</a>
                </li>

            </ul>
        </li>

        <!--Show-->
        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Show
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu show-dropdown" role="menu">

                <li>
                    <a class="disabled">Totals</a>
                </li>

                <!--Basic totals-->
                <li>
                    <a
                        v-on:click="show.basicTotals = !show.basicTotals"
                        class="pointer"
                    >
                        <span>Totals</span>
                        <i
                            v-show="show.basicTotals"
                            class="fa fa-check"></i>
                    </a>
                </li>

                <li role="separator" class="divider"></li>

                <li>
                    <a class="disabled">Transaction Fields</a>
                </li>

                <!--All-->
                <li>
                    <a
                        v-on:click="showAllTransactionProperties()"
                        :disabled="transactionPropertiesToShow.all"
                        href="#"
                    >
                        <span>All</span>
                        <i
                            v-show="transactionPropertiesToShow.all"
                            class="fa fa-check"></i>
                    </a>
                </li>

                <!--Date-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('date')"
                        href="#"
                    >
                        <span>Date</span>
                        <i
                            v-show="transactionPropertiesToShow.date"
                            class="fa fa-check"></i>
                    </a>
                </li>

                <!--Description-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('description')"
                        href="javascript:void(0)"
                    >
                        <span>Description</span>
                        <i
                            v-show="transactionPropertiesToShow.description"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

                <!--Merchant-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('merchant')"
                        href="javascript:void(0)"
                    >
                        <span>Merchant</span>
                        <i
                            v-show="transactionPropertiesToShow.merchant"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

                <!--Total-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('total')"
                        href="javascript:void(0)"
                    >
                        <span>Total</span>
                        <i
                            v-show="transactionPropertiesToShow.total"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

                <!--Account-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('account')"
                        href="javascript:void(0)"
                    >
                        <span>Account</span>
                        <i
                            v-show="transactionPropertiesToShow.account"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

                <!--Duration-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('duration')"
                        href="javascript:void(0)"
                    >
                        <span>Duration</span>
                        <i
                            v-show="transactionPropertiesToShow.duration"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

                <!--Reconciled-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('reconciled')"
                        href="javascript:void(0)"
                    >
                        <span>Reconciled</span>
                        <i
                            v-show="transactionPropertiesToShow.reconciled"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

                <!--Allocated-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('allocated')"
                        href="javascript:void(0)"
                    >
                        <span>Allocated</span>
                        <i
                            v-show="transactionPropertiesToShow.allocated"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

                <!--Budgets-->
                <li>
                    <a
                        v-on:click="toggleTransactionProperty('budgets')"
                        href="javascript:void(0)"
                    >
                        <span>Budgets</span>
                        <i
                            v-show="transactionPropertiesToShow.budgets"
                            class="fa fa-check"
                        >
                        </i>
                    </a>
                </li>

            </ul>
        </li>

        <!--User-->
        <li id="menu-dropdown" class="dropdown gravatar-li">
            <a href="#" data-toggle="dropdown">
                <?php echo Auth::user()->name; ?>
            </a>
            <a href="#" data-toggle="dropdown" class="gravatar-container">
                <img v-bind:src="me.gravatar" class="gravatar"/>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <!-- if they are a guest they shouldn't see this page anyway, but so that my code will work... -->
                <li><a href="/auth/logout">Logout</a></li>
                <li><a v-on:click="deleteUser()" href="#">Delete account</a></li>
            </ul>
        </li>

        <!--Budgets-->
        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Budgets
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">

                <li>
                    <a v-link="{path: '/fixed-budgets'}">Fixed</a>
                </li>

                <li>
                    <a v-link="{path: '/flex-budgets'}">Flex</a>
                </li>

                <li>
                    <a v-link="{path: '/unassigned-budgets'}">Unassigned</a>
                </li>

            </ul>
        </li>

        <!--Help-->
        <li id="menu-dropdown" class="dropdown">
            <a href="#" data-toggle="dropdown">
                Help
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a v-link="{path: '/help'}">Start here :)</a>
                </li>

                <li>
                    <a v-link="{path: '/feedback'}">Submit feedback</a>
                </li>
            </ul>
        </li>

        <li>
            <a v-on:click="toggleFilter()" class="fa fa-search"></a>
        </li>

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