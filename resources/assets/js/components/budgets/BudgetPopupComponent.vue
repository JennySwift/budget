<template>

    <popup
        popup-name="budget"
        id="budget-popup"
        :save="updateBudget"
        :destroy="deleteBudget"
        :redirect-to="redirectTo"
    >
        <div slot="content">
            <h3>Edit {{ shared.selectedBudget.name }}</h3>

            <div class="form-group">
                <label for="selected-budget-name">Name</label>
                <input
                    v-model="shared.selectedBudget.name"
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
                    v-model="shared.selectedBudget.type"
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

            <div v-show="shared.selectedBudget.type !== 'unassigned'" class="form-group">
                <label for="selected-budget-starting-date">Starting date</label>
                <input
                    v-model="shared.selectedBudget.formattedStartingDate"
                    type="text"
                    id="selected-budget-starting-date"
                    name="selected-budget-starting-date"
                    placeholder="starting date"
                    class="form-control"
                >
            </div>

            <div v-show="shared.selectedBudget.type !== 'unassigned'" class="form-group">
                <label v-if="shared.selectedBudget.type === 'fixed'" for="selected-budget-amount">Amount Per Month</label>
                <label v-if="shared.selectedBudget.type === 'flex'" for="selected-budget-amount">% of Remaining Balance</label>
                <input
                    v-model="shared.selectedBudget.amount"
                    type="text"
                    id="selected-budget-amount"
                    name="selected-budget-amount"
                    placeholder="amount"
                    class="form-control"
                >
            </div>
        </div>

    </popup>

</template>

<script>
    import TotalsRepository from '../../repositories/TotalsRepository'
    import helpers from '../../repositories/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
                types: ['fixed', 'flex', 'unassigned'],
                redirectTo: false
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            updateBudget: function (budget) {
                var data = {
                    name: this.shared.selectedBudget.name,
                    amount: this.shared.selectedBudget.amount,
                    type: this.shared.selectedBudget.type,
                    starting_date: helpers.convertToMySqlDate(this.shared.selectedBudget.formattedStartingDate),
                };

                TotalsRepository.resetTotalChanges();

                helpers.put({
                    url: '/api/budgets/' + this.shared.selectedBudget.id,
                    data: data,
                    property: 'budgets',
                    message: 'Budget updated',
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        //Update the budgets array
                        store.update(response, 'budgets');
                        if (this.page !== response.type) {
                            //The budget type has changed.
                            //Remove the budget from the specific budgets array it was in
                            this.removeBudgetFromArray(response, this.page);
                            //Add the budget to the correct array
                            switch(response.type) {
                                case 'fixed':
                                    store.add(response, 'fixedBudgets');
                                    break;
                                case 'flex':
                                    store.add(response, 'flexBudgets');
                                    break;
                                case 'unassigned':
                                    store.add(response, 'unassignedBudgets');
                                    break;
                            }
                        }
                        else {
                            //The budget type has not changed. Update the specific budgets array
                            switch(this.page) {
                                case 'fixed':
                                    store.update(response, 'fixedBudgets');
                                    break;
                                case 'flex':
                                    store.update(response, 'flexBudgets');
                                    break;
                                case 'unassigned':
                                    store.update(response, 'unassignedBudgets');
                                    break;
                            }
                        }

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
             * Used in both updating and deleting a budget, if the budget type is changed in the update
             */
            removeBudgetFromArray: function (budget, type) {
                switch(type) {
                    case 'fixed':
                        store.delete(budget, 'fixedBudgets');
                        break;
                    case 'flex':
                        store.delete(budget, 'flexBudgets');
                        break;
                    case 'unassigned':
                        store.delete(budget, 'unassignedBudgets');
                        break;
                }
            },

            /**
             *
             */
            deleteBudget: function () {
                helpers.delete({
                    url: '/api/budgets/' + this.shared.selectedBudget.id,
                    array: 'budgets',
                    itemToDelete: this.shared.selectedBudget,
                    message: 'Budget deleted',
                    redirectTo: this.redirectTo,
                    confirmMessageTitle: 'Are you sure?',
                    confirmMessageText: 'You have ' + this.shared.selectedBudget.transactionsCount + ' transactions with this budget.',
                    callback: function () {
                        this.removeBudgetFromArray(this.shared.selectedBudget, this.shared.selectedBudget.type);
                    }.bind(this)
                });
            }
        },
        props: [
            'page'
        ],
        mounted: function () {

        }
    }
</script>
