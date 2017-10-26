<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        class="popup-outer"
    >

        <div id="mass-transaction-update-popup" class="popup-inner">

            <div class="messages">
                <div v-if="!showProgress">
                    <div>Clicking 'Go' will add the chosen budgets to the {{ transactions.length }} transactions that you can see on the page.</div>
                    <div class="warning">Duplicate budgets will be removed from your transactions, however, the allocation given to the budgets you choose here will be 100% of the transaction. So you may need to reallocate the transactions after doing this.</div>
                </div>
                <h5 v-if="count < transactions.length && showProgress">Updating {{ transactions.length }} transactions</h5>
                <h5 v-if="count === transactions.length">Done! The selected budgets have been added to {{ transactions.length }} transactions.</h5>
            </div>

            <div v-show="showProgress" class="progress">
                <div
                    class="progress-bar progress-bar-success progress-bar-striped"
                    role="progressbar"
                    <!--aria-valuenow="40"-->
                    aria-valuemin="0"
                    aria-valuemax="100"
                    v-bind:style="{width: progressWidth + '%'}"
                >

                    {{ count }}

                </div>
            </div>

            Add budgets to transactions

            <autocomplete
                autocomplete-id="mass-transaction-update-budgets-autocomplete"
                input-id="mass-transaction-update-budgets-input"
                :unfiltered-options="shared.budgets"
                prop="name"
                multiple-selections="true"
                :chosen-options="budgetsToAdd"
            >
            </autocomplete>


            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Close</button>
                <button v-on:click="addBudgetsToTransactions()" class="btn btn-success">Go</button>
            </div>

        </div>
    </div>
</template>

<script>
    import Helpers from '../../repositories/Helpers'
    import $ from 'jquery'
    export default {
        data: function () {
            return {
                showPopup: false,
                budgetsToAdd: [],
                count: 0,
                showProgress: false,
                shared: store.state
            };
        },
        components: {},
        computed: {
            progressWidth: function () {
                return 100 / (this.shared.transactions.length / this.count);
            },
            transactions: function () {
                return this.shared.transactions;
            }
        },
        methods: {

            /**
             *
             */
            addBudgetsToTransactions: function () {
                this.count = 0;
                this.showProgress = true;
                for (var i = 0; i < this.shared.transactions.length; i++) {
                    this.addBudgetsToTransaction(this.transactions[i]);
                }
            },

            /**
             *
             * @param transaction
             */
            addBudgetsToTransaction: function (transaction) {
                $.event.trigger('show-loading');

                var data = {
                    addingBudgets: true,
                    budget_ids: _.map(this.budgetsToAdd, 'id')
                };

                helpers.put({
                    url: '/api/transactions/' + transaction.id,
                    data: data,
                    property: 'stuffs',
                    message: 'Stuff updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        var index = _.indexOf(this.transactions, _.findWhere(this.transactions, {id: transaction.id}));
                        this.transactions[index].budgets = response.budgets;
                        this.transactions[index].multipleBudgets = response.multipleBudgets;
                        this.transactions[index].validAllocation = response.validAllocation;
                        this.count++;

                        if (this.count === this.shared.transactions.length) {
                            //$.event.trigger('provide-feedback', ['Done!', 'success']);
                            //this.showPopup = false;
                        }
                    }.bind(this)
                });
            },

            /**
             *
             */
            closePopup: function (event) {
                Helpers.closePopup(event, this);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-mass-transaction-update-popup', function (event) {
                    that.showPopup = true;
                    that.showProgress = false;
                    that.budgetsToAdd = [];
                });
            }
        },
        props: [

        ],
        mounted: function () {
            this.listen();
        }
    }
</script>
