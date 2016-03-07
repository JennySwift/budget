<dropdown
        inline-template
        animate-in-class="flipInX"
        animate-out-class="flipOutX"
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
                    <li><a href="#concept-purpose-goal">Concept/Purpose/Goal</a></li>
                    <li><a href="#tags-link">Tags</a></li>
                    <li><a href="#accounts-link">Accounts</a></li>
                    <li><a href="#transactions-link">Transactions</a></li>
                    <ul>
                        <li><a href="#allocating">Allocating the totals</a></li>
                        <li><a href="#reconciling">Reconciling</a></li>
                    </ul>
                    <li><a href="#savings">Savings</a></li>
                    <li><a href="#RB">Remaining Balance</a></li>
                    <li><a href="#totals">Totals</a></li>
                    <li><a href="#graphs-help">Graphs</a></li>
                </ul>
            </div>
        </div>

    </div>

</dropdown>