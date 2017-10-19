<template>
    <div
        id="new-transaction-container"
    >
        <div
            v-show="shared.show.newTransaction"
            v-bind:style="{color: shared.me.preferences.colors[shared.newTransaction.type]}"
            id="new-transaction"
        >

            <favourites></favourites>

            <type></type>

            <div>
                <date></date>
            </div>

            <total></total>

            <div>
                <merchant></merchant>
                <description></description>
            </div>

            <div>
                <accounts></accounts>
                <reconcile></reconcile>

            </div>

            <div>
                <budgets></budgets>

                <duration></duration>
            </div>

            <div>
                <enter></enter>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import TotalsRepository from '../../repositories/TotalsRepository'
    import TransactionsRepository from '../../repositories/TransactionsRepository'
    import TypeComponent from './TypeComponent.vue'
    import DateComponent from './DateComponent.vue'
    import TotalComponent from './TotalComponent.vue'
    import MerchantComponent from './MerchantComponent.vue'
    import DescriptionComponent from './DescriptionComponent.vue'
    import DurationComponent from './DurationComponent.vue'
    import EnterComponent from './EnterComponent.vue'
    import ReconcileComponent from './ReconcileComponent.vue'
    import BudgetsComponent from './BudgetsComponent.vue'
    import AccountsComponent from './AccountsComponent.vue'
    import FavouritesComponent from './FavouritesComponent.vue'

    export default {
        data: function () {
            return {
                dropdown: {},
                shared: store.state,
                colors: {
                    newTransaction: {}
                },
            };
        },
        components: {
            'type': TypeComponent,
            'date': DateComponent,
            'total': TotalComponent,
            'merchant': MerchantComponent,
            'description': DescriptionComponent,
            'duration': DurationComponent,
            'enter': EnterComponent,
            'reconcile': ReconcileComponent,
            'budgets': BudgetsComponent,
            'accounts': AccountsComponent,
            'favourites': FavouritesComponent,
        },
        computed: {
            //Putting this here so it isn't undefined in my test
//            me: function () {
//                return me;
//            },
//            env: function () {
//                return env;
//            },
            accounts: function () {
                //So the balance isn't included, messing up the autocomplete
                return _.map(this.shared.accounts, function (account) {
                    return _.pick(account, 'id', 'name');
                });
            }
        },
        methods: {

            /**
             *
             */
            clearNewTransactionFields: function () {
                this.shared.newTransaction = store.clearNewTransactionFields(this.shared.newTransaction);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('accounts-loaded', function (event) {
                    store.getNewTransactionDefaults();
                });

            }

        },
        props: [
            'tab'
        ],
        mounted: function () {
            this.listen();
            store.getNewTransactionDefaults();
        }
    }
</script>

