<dropdown
        inline-template
        animate-in-class="flipInX"
        animate-out-class="flipOutX"
        :scroll-to="scrollTo"
        id="help-navigation-container"
        class="dropdown-directive"
>

    <div>

        <button
                v-on:click="toggleDropdown()"
                tabindex="-1"
                class="btn btn-info"
        >
            Navigation
            <span class="caret"></span>
        </button>

        <div class="dropdown-content animated">
            <div id="help-navigation" class="help">
                <ul>
                    <li>
                        <a
                            v-on:click="scrollTo('concept-purpose-goal')"
                            href="javascript:void(0)"
                        >
                            Concept/Purpose/Goal
                        </a>
                    </li>

                    <li>
                        <a
                            v-on:click="scrollTo('tags-link')"
                            href="javascript:void(0)"
                        >
                            Tags
                        </a>
                    </li>

                    <li>
                        <a
                                v-on:click="scrollTo('accounts-link')"
                                href="javascript:void(0)"
                        >

                            Accounts
                        </a>
                    </li>

                    <li>
                        <a
                            v-on:click="scrollTo('transacations-link')"
                            href="javascript:void(0)"
                        >
                            Transactions
                        </a>
                    </li>

                    <ul>
                        <li>
                            <a
                                v-on:click="scrollTo('allocation')"
                                href="javascript:void(0)"
                            >
                                Allocating the totals
                            </a>
                        </li>

                        <li>
                            <a
                                v-on:click="scrollTo('reconciling')"
                                href="javascript:void(0)"
                            >
                                Reconciling
                            </a>
                        </li>
                    </ul>

                    <li>
                        <a
                            v-on:click="scrollTo('reconciling')"
                            href="javascript:void(0)"
                        >

                            Savings
                        </a>
                    </li>

                    <li>
                        <a
                            v-on:click="scrollTo('RB')"
                            href="javascript:void(0)"
                        >
                            Remaining Balance
                        </a>
                    </li>

                    <li>
                        <a
                            v-on:click="scrollTo('totals')"
                            href="javascript:void(0)"
                        >
                            Totals
                        </a>
                    </li>

                    <li>
                        <a
                            v-on:click="scrollTo('graphs-help')"
                            href="javascript:void(0)"
                        >
                            Graphs
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>

</dropdown>