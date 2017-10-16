<template>
    <div
        id="new-transaction-container"
    >
        <div
            v-show="showNewTransaction"
            v-bind:style="{color: shared.me.preferences.colors[newTransaction.type]}"
            id="new-transaction"
        >

            <div class="form-group">
                <autocomplete
                    input-label="Favourites"
                    id-to-focus-after-autocomplete=""
                    autocomplete-id="new-transaction-favourites"
                    autocomplete-field-id="new-transaction-favourites-input"
                    :unfiltered-autocomplete-options="favouriteTransactions"
                    prop="name"
                    label-for-option=""
                    :function-on-enter=""
                    :function-when-option-is-chosen="fillFields"
                    :model.sync="selectedFavouriteTransaction"
                    <!--For some reason this is erroring after upgrade-->
                    <!--clear-field-on-focus="true"-->
                >
                </autocomplete>
            </div>

            <!--Type-->
            <div class="type">
                <div class="btn-group">
                    <button v-on:click="newTransaction.type = 'income'" v-bind:style="{background: shared.me.preferences.colors.income}" class="btn">Credit</button>
                    <button v-on:click="newTransaction.type = 'expense'" v-bind:style="{background: shared.me.preferences.colors.expense}" class="btn">Debit</button>
                    <button v-on:click="newTransaction.type = 'transfer'" v-bind:style="{background: shared.me.preferences.colors.transfer}" class="btn">Transfer</button>
                </div>
            </div>

            <div>
                <!--Date-->
                <div>
                    <!--Date help-->
                    <div class="help-row">
                        <label>Enter the transaction's date</label>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>
                                <button v-on:click="toggleDropdown()" class="btn btn-info btn-xs">
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <h3>Acceptable formats</h3>

                                        <div>
                                            <div>Days and months are case-insensitive and can be abbreviated (eg: mon or jan) or written in
                                                full.
                                            </div>
                                            <div>Years can be written as either yy or yyyy.</div>
                                        </div>

                                        <div>
                                            <div>today</div>
                                            <div>yesterday</div>
                                            <div>tomorrow</div>
                                            <div>next Thursday</div>
                                            <div>last Thursday</div>
                                            <div>Thursday (The most recent Thursday)</div>
                                            <div>date/month (In the current year. Example: 31/12, or 1/1.)</div>
                                            <div>date/month/year</div>
                                            <div>12 Jan (In the current year)</div>
                                            <div>12 Jan 15</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </dropdown>

                    </div>

                    <input
                        v-model="newTransaction.userDate"
                        v-on:keyup.13="insertTransaction()"
                        id="date"
                        placeholder="date"
                        type='text'
                        class="date mousetrap form-control">
                </div>


                <!--Total-->
                <div>
                    <div class="help-row">
                        <label>Enter the total</label>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>
                                <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <div>Enter a value without a dollar sign. No need to add a negative sign for an expense transaction.</div>
                                        <div>For example:</div>
                                        <div>
                                            <div>5</div>
                                            <div>5.5</div>
                                            <div>5.55</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </dropdown>
                    </div>

                    <input
                        v-model="newTransaction.total"
                        v-on:keyup.13="insertTransaction()"
                        class="total mousetrap form-control"
                        placeholder="$"
                        type='text'
                    >
                </div>

            </div>

            <div>
                <!--Merchant-->
                <div class="autocomplete-container">

                    <div class="help-row">
                        <label>Enter a merchant (optional)</label>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>

                                <button
                                    v-on:click="toggleDropdown()"
                                    tabindex="-1"
                                    class="btn btn-info btn-xs"
                                >
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <div>As you type a merchant, previous transactions with a matching merchant will show up.</div>
                                        <div>To fill in the fields with those of a previous transaction, either:</div>
                                        <ol>
                                            <li>Use the up and down arrow keys to select a previous transaction, then press enter.</li>
                                            <li>Click on one of the previous transactions.</li>
                                        </ol>
                                        <div>Alternatively, you can just keep typing if you don't want to use the autocomplete.</div>
                                    </div>
                                </div>

                            </div>

                        </dropdown>
                    </div>

                    <input
                        v-if="!shared.me.preferences.autocompleteMerchant"
                        v-model="newTransaction.merchant"
                        v-on:keyup.13="insertTransaction()"
                        class="form-control"
                        placeholder="merchant"
                        type='text'
                    >

                    <transaction-autocomplete
                        v-if="shared.me.preferences.autocompleteMerchant"
                        placeholder="merchant"
                        id="new-transaction-merchant"
                        :typing.sync="newTransaction.merchant"
                        :new-transaction.sync="newTransaction"
                        :function-on-enter="insertTransaction"
                    >
                    </transaction-autocomplete>

                </div>
                <!--Description-->
                <div class="autocomplete-container">

                    <div class="help-row">
                        <label>Enter a description (optional)</label>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>

                                <button
                                    v-on:click="toggleDropdown()"
                                    tabindex="-1"
                                    class="btn btn-info btn-xs"
                                >
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <div>As you type a description, previous transactions with a matching description will show up.</div>
                                        <div>To fill in the fields with those of a previous transaction, either:</div>
                                        <ol>
                                            <li>Use the up and down arrow keys to select a previous transaction, then press enter.</li>
                                            <li>Click on one of the previous transactions.</li>
                                        </ol>
                                        <div>Alternatively, you can just keep typing if you don't want to use the autocomplete.</div>
                                    </div>
                                </div>

                            </div>

                        </dropdown>
                    </div>

                    <input
                        v-if="!shared.me.preferences.autocompleteDescription"
                        v-model="newTransaction.description"
                        v-on:keyup.13="insertTransaction()"
                        class="form-control"
                        placeholder="description"
                        type='text'
                    >

                    <transaction-autocomplete
                        v-if="shared.me.preferences.autocompleteDescription"
                        placeholder="description"
                        id="new-transaction-description"
                        :typing.sync="newTransaction.description"
                        :new-transaction.sync="newTransaction"
                        :function-on-enter="insertTransaction"
                    >
                    </transaction-autocomplete>

                </div>
            </div>

            <div>
                <!--Accounts-->
                <div v-cloak v-show=" newTransaction.type !== 'transfer'">

                    <div class="form-group">
                        <label for="new-transaction-account">Account</label>

                        <select
                            v-model="newTransaction.account"
                            v-on:keyup.13="insertTransaction()"
                            id="new-transaction-account"
                            class="form-control"
                        >
                            <option
                                v-for="account in accounts"
                                v-bind:value="account"
                            >
                                {{ account.name }}
                            </option>
                        </select>
                    </div>

                </div>

                <div v-cloak v-show=" newTransaction.type === 'transfer'">

                    <div class="form-group">
                        <label for="new-transaction-from-account">Select the account your are transferring money from</label>

                        <select
                            v-model="newTransaction.fromAccount"
                            v-on:keyup.13="insertTransaction()"
                            id="new-transaction-from-account"
                            class="form-control"
                        >
                            <option
                                v-for="account in accounts"
                                v-bind:value="account"
                            >
                                {{ account.name }}
                            </option>
                        </select>
                    </div>

                </div>

                <div v-cloak v-show=" newTransaction.type === 'transfer'">

                    <div class="form-group">
                        <label for="new-transaction-to-account">Select the account you are transferring money to</label>

                        <select
                            v-model="newTransaction.toAccount"
                            v-on:keyup.13="insertTransaction()"
                            id="new-transaction-to-account"
                            class="form-control"
                        >
                            <option
                                v-for="account in accounts"
                                v-bind:value="account"
                            >
                                {{ account.name }}
                            </option>
                        </select>
                    </div>

                </div>

                <!--Reconcile-->
                <div class="reconcile">

                    <div class="help-row">
                        <label for="new-transaction-reconciled">Check this box if your transaction is reconciled</label>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>

                                <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <div>The reconciled checkbox is so you can compare the transactions you enter here with those on your bank statement, verifying them, keeping them in sync.</div>
                                        <div>You can use the filter (click the magnifying glass icon at the top of the page) to see transactions that are/are not reconciled.</div>
                                        <div>If you don't reconcile the transaction now, you can reconcile it later by checking the reconciled checkbox for the transaction in the table below.</div>
                                    </div>
                                </div>

                            </div>

                        </dropdown>
                    </div>

                    <input v-model="newTransaction.reconciled" id="new-transaction-reconciled" type="checkbox">

                    <!--<checkbox-->
                    <!--model="new_transaction.reconciled"-->
                    <!--id="new-transaction-reconciled">-->
                    <!--</checkbox>-->

                </div>
            </div>

            <div>
                <!--Budgets-->
                <div v-if="newTransaction.type !== 'transfer'">
                    <div class="help-row">
                        <label>Add tags to your transaction (optional)</label>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>

                                <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <div>To add tags to your transaction, start typing the name of your tag in the field, then use the up or down arrow keys to select a tag, then press enter.</div>
                                        <div>Repeat the process to enter more than one tag.</div>
                                        <div>The tag must first be created on the <a href="/tags">tags page</a> in order for it to show up as an option here.</div>
                                        <div>If you press enter with no tag selected, the transaction will be entered.</div>
                                    </div>
                                </div>

                            </div>

                        </dropdown>
                    </div>

                    <budget-autocomplete
                        :chosen-budgets.sync="newTransaction.budgets"
                        :budgets="shared.budgets"
                        multiple-budgets="true"
                        :function-on-enter="insertTransaction"
                    >
                    </budget-autocomplete>

                </div>

                <!--Duration-->
                <div>
                    <div class="help-row">
                        <label>Duration (optional)
                        </label>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>

                                <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <div>Enter the duration in H:M format.</div>
                                    </div>
                                </div>

                            </div>

                        </dropdown>
                    </div>

                    <input
                        v-model="newTransaction.duration"
                        v-on:keyup.13="insertTransaction()"
                        class="mousetrap form-control"
                        placeholder="H:M"
                        type='text'>
                </div>

            </div>

            <div>
                <!--Enter-->
                <div>
                    <div class="help-row">
                        <button v-on:mousedown="insertTransactionPreparation()" tabindex="-1" id="add-transaction" class="btn btn-success">Add transaction</button>

                        <dropdown
                            inline-template
                            animate-in-class="flipInX"
                            animate-out-class="flipOutX"
                            class="dropdown-directive"
                        >

                            <div>

                                <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                                    Help
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-content animated">
                                    <div class="help">
                                        <div>You can also add a new transaction by pressing enter on your keyboard when the cursor is in one of the inputs (unless pressing enter is to select an item from one of the dropdown menus).</div>
                                    </div>
                                </div>

                            </div>

                        </dropdown>
                    </div>

                </div>
            </div>

        </div>


    </div>
</template>

<script>
    import $ from 'jquery'
    import TotalsRepository from '../../repositories/TotalsRepository'
    import TransactionsRepository from '../../repositories/TransactionsRepository'

    export default {
        data: function () {
            return {
                dropdown: {},
                showNewTransaction: false,
                types: ["income", "expense", "transfer"],
                shared: store.state,
                selectedFavouriteTransaction: {},
                colors: {
                    newTransaction: {}
                },
            };
        },
        components: {},
        computed: {
            favouriteTransactions: function () {
                return this.shared.favouriteTransactions;
            },
            newTransaction: function () {
                return this.shared.newTransaction.defaults;
            },
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
                this.newTransaction = store.clearNewTransactionFields(this.newTransaction);
            },

            /**
             * This is not for the transaction autocomplete,
             * which is in the TransactionAutocomplete directive.
             * I think it is for the favourite transactions feature.
             */
            fillFields: function () {
                this.newTransaction.description = this.selectedFavouriteTransaction.description;
                this.newTransaction.merchant = this.selectedFavouriteTransaction.merchant;
                this.newTransaction.total = this.selectedFavouriteTransaction.total;
                this.newTransaction.type = this.selectedFavouriteTransaction.type;
                this.newTransaction.budgets = this.selectedFavouriteTransaction.budgets;

                if (this.newTransaction.type === 'transfer') {
                    this.newTransaction.fromAccount = this.selectedFavouriteTransaction.fromAccount;
                    this.newTransaction.toAccount = this.selectedFavouriteTransaction.toAccount;
                }
                else {
                    this.newTransaction.account = this.selectedFavouriteTransaction.account;
                }
            },

            /**
             * Return true if there are errors.
             * @returns {boolean}
             */
            anyErrors: function () {
                var errorMessages = store.anyNewTransactionErrors(this.newTransaction);

                if (errorMessages) {
                    for (var i = 0; i < errorMessages.length; i++) {
                        $.event.trigger('provide-feedback', [errorMessages[i], 'error']);
                    }

                    return true;
                }

                return false;
            },

            /**
             *
             */
            insertTransactionPreparation: function () {
                if (!this.anyErrors()) {
                    TotalsRepository.resetTotalChanges();

                    if (this.newTransaction.type === 'transfer') {
                        var that = this;
                        this.insertTransaction('from');
                        setTimeout(function(){
                            that.insertTransaction('to');
                        }, 100);
                    }
                    else {
                        this.insertTransaction();
                    }
                }
            },

            /**
             *
             */
            insertTransaction: function (direction) {
                var data = TransactionsRepository.setFields(this.newTransaction);

                if (direction) {
                    //It is a transfer transaction
                    data.direction = direction;

                    if (direction === 'from') {
                        data.account_id = this.newTransaction.fromAccount.id;
                    }
                    else if (direction === 'to') {
                        data.account_id = this.newTransaction.toAccount.id;
                    }
                }

                helpers.post({
                    url: '/api/transactions',
                    data: data,
                    message: 'Transaction created',
                    clearFields: this.clearFields,
                    callback: function (response) {
                        this.insertTransactionResponse(response);
                    }.bind(this)
                });
            },

            /**
             *
             * @param response
             */
            insertTransactionResponse: function (response) {
                TotalsRepository.getSideBarTotals(this);
                this.clearNewTransactionFields();
                //this.newTransaction.dropdown = false;

                if (response.multipleBudgets) {
                    $.event.trigger('show-allocation-popup', [response, true]);
                    //We'll run the filter after the allocation has been dealt with
                }
                else {
                    FilterRepository.runFilter(this);
                }
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

