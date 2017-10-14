<template>
    <div
        v-show="showPopup"
        v-on:click="closePopup($event)"
        id="edit-budget"
        class="popup-outer">

        <div class="popup-inner">
            <h3>Edit {{ selectedBudget.name }}</h3>

            <div class="form-group">
                <label for="selected-budget-name">Name</label>
                <input
                    v-model="selectedBudget.name"
                    type="text"
                    id="selected-budget-name"
                    name="selected-budget-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="selected-budget-type">Type</label>

                <select
                    v-model="selectedBudget.type"
                    id="selected-budget-type"
                    class="form-control"
                >
                    <option
                        v-for="type in types"
                        v-bind:value="type"
                    >
                        {{ type }}
                    </option>
                </select>
            </div>

            <div v-show="selectedBudget.type !== 'unassigned'" class="form-group">
                <label for="selected-budget-starting-date">Starting date</label>
                <input
                    v-model="selectedBudget.formattedStartingDate"
                    type="text"
                    id="selected-budget-starting-date"
                    name="selected-budget-starting-date"
                    placeholder="starting date"
                    class="form-control"
                >
            </div>

            <div v-show="selectedBudget.type !== 'unassigned'" class="form-group">
                <label v-if="selectedBudget.type === 'fixed'" for="selected-budget-amount">Amount Per Month</label>
                <label v-if="selectedBudget.type === 'flex'" for="selected-budget-amount">% of Remaining Balance</label>
                <input
                    v-model="selectedBudget.amount"
                    type="text"
                    id="selected-budget-amount"
                    name="selected-budget-amount"
                    placeholder="amount"
                    class="form-control"
                >
            </div>

            <div class="buttons">
                <button v-on:click="showPopup = false" class="btn btn-default">Cancel</button>
                <button v-on:click="deleteBudget(budget)" class="btn btn-danger">Delete</button>
                <button v-on:click="updateBudget()" class="btn btn-success">Save</button>
            </div>
        </div>

    </div>

</template>

<script>
    import TotalsRepository from '../repositories/TotalsRepository'
    import helpers from '../repositories/Helpers'
    export default {
        data: function () {
            return {
                showPopup: false,
                selectedBudget: {},
                types: ['fixed', 'flex', 'unassigned']
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateBudget: function (budget) {
                var data = {
                    name: this.selectedBudget.name,
                    amount: this.selectedBudget.amount,
                    type: this.selectedBudget.type,
                    starting_date: helpers.formatDate(this.selectedBudget.formattedStartingDate),
                };

                TotalsRepository.resetTotalChanges();

                helpers.put({
                    url: '/api/budgets/' + this.selectedBudget.id,
                    data: data,
                    property: 'budgets',
                    message: 'Budget updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.updateBudget(response, this);
                        this.updateBudgetTableTotals();
                        TotalsRepository.getSideBarTotals(this);
                    }.bind(this)
                });
            },

            /**
             *
             */
            updateBudgetTableTotals: function () {
                if (this.page == 'fixed') {
                    $.event.trigger('update-fixed-budget-table-totals');
                }
                else if (this.page == 'flex') {
                    $.event.trigger('update-flex-budget-table-totals');
                }
            },

            /**
             *
             */
            deleteBudget: function () {
                helpers.delete({
                    url: '/api/budgets/' + this.selectedBudget.id,
                    array: 'budgets',
                    itemToDelete: this.budget,
                    message: 'Budget deleted',
                    redirectTo: this.redirectTo,
                    confirmMessage: 'You have ' + this.selectedBudget.transactionsCount + ' transactions with this budget. Are you sure you want to delete it?',
                    callback: function () {
                        this.showPopup = false;
                    }.bind(this)
                });
            },

            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('show-budget-popup', function (event, budget) {
                    that.selectedBudget = budget;
                    that.showPopup = true;
                });
            }
        },
        props: [
            'page'
        ],
        mounted: function () {
            this.listen();
        }
    }
</script>
