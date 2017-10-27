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
                <total></total>
            </div>

            <div>
                <merchant></merchant>
            </div>

           <div>
               <description></description>
           </div>
            <!--<div id="merchant-description-container">-->
                <!---->
            <!--</div>-->

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


        },
        props: [
            'tab'
        ],
        mounted: function () {

        }
    }
</script>

<style lang="scss" type="text/scss">

    #new-transaction-container {
        display: flex;
        justify-content: center;
        #new-transaction {
            animation: slideInDown .5s;
            margin-bottom: 20px;
            width: 600px;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            padding: 15px 25px;
            //box-shadow: 3px 3px 5px #777;
            border: 1px solid #777;
            > div {
                display: flex;
                justify-content: space-between;
                margin: 12px 0;
                &.type {
                    justify-content: center;
                }
                > div {
                    margin: 0px 20px;
                    width: 200px;
                }
            }
            .no-results {
                text-align: center;
                border: 1px solid;
                padding: 3px 0;
                border-radius: 3px;
                //box-shadow: 2px 2px 5px #777;
                margin: 8px 5px;
            }
            .btn-wrapper {
                width: 100%;
                display: flex;
                justify-content: space-between;
            }
            .help-row {
                display: flex;
                justify-content: space-between;
                .help {
                    > div {
                        margin: 15px 0;
                    }
                }
            }
            label {
                max-width: 210px;
            }
            input {
                margin-bottom: 4px;
                flex-grow: 1;
            }
            #new-transaction-favourites {
                width: 100%;
            }


            #new-transaction-merchant, #new-transaction-description {
                width: 100%;
                #new-transaction-merchant-autocomplete, #new-transaction-description-autocomplete {
                    .autocomplete-option {
                        display: table-row-group;
                    }
                }
            }
        }
    }
</style>

