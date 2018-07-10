<template>
    <div v-show="shared.show.newBudget" class="new-budget">
        <h3>Create a new budget</h3>
        <i v-on:click="showNewBudget = false" class="close fa fa-times"></i>

        <div class="form-group">
            <label for="new-budget-name">Name</label>
            <input
                v-model="newBudget.name"
                v-on:keyup.13="insertBudget()"
                type="text"
                id="new-budget-name"
                name="new-budget-name"
                placeholder="name"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="new-budget-type">Type</label>

            <select
                v-model="newBudget.type"
                v-on:keyup.13="insertBudget()"
                id="new-budget-type"
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

        <div v-if="newBudget.type !== 'unassigned'" class="form-group">
            <label v-if="newBudget.type === 'fixed'" for="new-budget-amount">Amount Per Month</label>
            <label v-if="newBudget.type === 'flex'" for="new-budget-amount">% of Remaining Balance</label>
            <input
                v-model="newBudget.amount"
                v-on:keyup.13="insertBudget()"
                type="text"
                id="new-budget-amount"
                name="new-budget-amount"
                placeholder="amount"
                class="form-control"
            >
        </div>

        <div v-if="newBudget.type !== 'unassigned'" class="form-group">
            <label for="new-budget-starting-date">Starting date</label>
            <input
                v-model="newBudget.startingDate"
                v-on:keyup.13="insertBudget()"
                type="text"
                id="new-budget-starting-date"
                name="new-budget-starting-date"
                placeholder="starting date"
                class="form-control"
            >
        </div>

        <div class="form-group">
            <button
                v-on:click="insertBudget()"
                class="btn btn-success"
            >
                Create Budget
            </button>
        </div>

    </div>
</template>

<script>
    import TotalsRepository from '../../repositories/TotalsRepository'
    import helpers from '../../repositories/helpers/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
                newBudget: {
                    type: 'fixed'
                },
                types: ['fixed', 'flex', 'unassigned']
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            insertBudget: function () {
                var data = {
                    name: this.newBudget.name,
                    type: this.newBudget.type,
                    amount: this.newBudget.amount,
                    starting_date: helpers.convertToMySqlDate(this.newBudget.startingDate),
                };

                TotalsRepository.resetTotalChanges();

                helpers.post({
                    url: '/api/budgets',
                    data: data,
                    array: 'budgets',
                    message: 'Budget created',
                    clearFields: this.clearFields,
                    redirectTo: this.redirectTo,
                    callback: function (response) {
                        store.addBudgetToSpecificArray(response, this);
                        TotalsRepository.getSideBarTotals(this);
                        this.updateBudgetTableTotals();
                    }.bind(this)
                });
            },

            /**
             *
             */
            updateBudgetTableTotals: function () {
                if (this.page == 'fixedBudgets') {
                    $.event.trigger('update-fixed-budget-table-totals');
                }
                else if (this.page == 'flexBudgets') {
                    $.event.trigger('update-flex-budget-table-totals');
                }
            }
        },
        props: [
            'page'
        ],
        mounted: function () {

        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../sass/variables';
    .new-budget {
        max-width: 433px;
        margin: auto;
        border-radius: 4px;
        box-shadow: 3px 3px 5px #777;
        margin-bottom: 34px;
        margin-top: 20px;
        padding: 10px 15px;
        border: 1px solid #777;
        position: relative;
        .close {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 20px;
            color: $danger;
            cursor: pointer;
        }
        label {
            display: block;
        }
        input, select {
            margin-bottom: 10px;
        }
        .flex {
            display: flex;
            div:first-child {
                margin-right: 20px;
            }
        }
    }
</style>
