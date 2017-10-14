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
            <router-link to="/" class="fa fa-home"></router-link>
        </li>

        <li>
            <router-link to="/graphs">Graphs</router-link>
        </li>

        <li id="menu-dropdown" class="dropdown">
            <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">

                <li>
                    <router-link to="/accounts">Accounts</router-link>
                </li>

                <li>
                    <router-link to="/preferences">Preferences</router-link>
                </li>

                <li>
                    <router-link to="/favourite-transactions">Favourite transactions</router-link>
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
                        v-on:click="shared.transactionPropertiesToShow.basicTotals = !shared.transactionPropertiesToShow.basicTotals"
                        class="pointer"
                    >
                        <span>Totals</span>
                        <i
                            v-show="shared.transactionPropertiesToShow.basicTotals"
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
                        :disabled="shared.transactionPropertiesToShow.all"
                        href="#"
                    >
                        <span>All</span>
                        <i
                            v-show="shared.transactionPropertiesToShow.all"
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
                            v-show="shared.transactionPropertiesToShow.date"
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
                            v-show="shared.transactionPropertiesToShow.description"
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
                            v-show="shared.transactionPropertiesToShow.merchant"
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
                            v-show="shared.transactionPropertiesToShow.total"
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
                            v-show="shared.transactionPropertiesToShow.account"
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
                            v-show="shared.transactionPropertiesToShow.duration"
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
                            v-show="shared.transactionPropertiesToShow.reconciled"
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
                            v-show="shared.transactionPropertiesToShow.allocated"
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
                            v-show="shared.transactionPropertiesToShow.budgets"
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
                <img v-bind:src="shared.me.gravatar" class="gravatar"/>
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
                    <router-link to="/fixed-budgets">Fixed</router-link>
                </li>

                <li>
                    <router-link to="flex-budgets">Flex</router-link>
                </li>

                <li>
                    <router-link to="/unassigned-budgets">Unassigned</router-link>
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
                    <router-link to="/help">Start here :)</router-link>
                </li>

                <!--<li>-->
                    <!--<router-link to="/feedback">Submit feedback</router-link>-->
                <!--</li>-->
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
                shared: store.state,
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
                this.shared.transactionPropertiesToShow = store.setTransactionDefaults();
            },

            /**
             *
             * @param property
             */
            toggleTransactionProperty: function (property) {
                this.shared.transactionPropertiesToShow[property] = !this.shared.transactionPropertiesToShow[property];
                this.shared.transactionPropertiesToShow.all = this.calculateIfAllTransactionPropertiesAreShown();
            },

            /**
             *
             * @returns {*}
             */
            calculateIfAllTransactionPropertiesAreShown: function () {
                var that = this;
                var allShown = true;
                $.each(this.shared.transactionPropertiesToShow, function (key, value) {
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
//            'show',
//            'transactionPropertiesToShow'
        ],
        mounted: function () {

        }
    }
</script>