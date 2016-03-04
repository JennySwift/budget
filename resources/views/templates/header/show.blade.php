<li id="menu-dropdown" class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        Show
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu show-dropdown" role="menu">

        <li>
            <a class="disabled">Totals</a>
        </li>

        {{--Basic totals--}}
        <li>
            <a
                    v-on:click="show.basicTotals = !show.basicTotals"
                    href="#"
            >
                <span>Basic Totals</span>
                <i
                        v-show="show.basicTotals"
                        class="fa fa-check"></i>
            </a>
        </li>

        {{--Budget totals--}}
        <li>
            <a
                    v-on:click="show.budgetTotals = !show.budgetTotals"
                    href="#"
            >
                <span>Remaining Balance Totals</span>
                <i
                        v-show="show.budgetTotals"
                        class="fa fa-check"></i>
            </a>
        </li>

        <li role="separator" class="divider"></li>

        <li>
            <a class="disabled">Transaction Fields</a>
        </li>

        {{--All--}}
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

        {{--Date--}}
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

        {{--Description--}}
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

        {{--Merchant--}}
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

        {{--Total--}}
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

        {{--Account--}}
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

        {{--Duration--}}
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

        {{--Reconciled--}}
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

        {{--Allocated--}}
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

        {{--Budgets--}}
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