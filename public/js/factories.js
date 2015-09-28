app.factory('AccountsFactory', function ($http) {
    return {
        getAccounts: function () {

            var $url = '/api/accounts';

            return $http.get($url);
        },
        insertAccount: function () {
            var $url = '/api/accounts';
            var $data = {
                name: $(".new_account_input").val()
            };

            return $http.post($url, $data);
        },
        updateAccountName: function ($account) {
            var $url = $account.path;
            var $data = { name: $account.name };

            return $http.put($url, $data);
        },
        deleteAccount: function ($account) {
            var $url = $account.path;

            return $http.delete($url);
        }

    };
});
app.factory('AutocompleteFactory', function ($http) {
	var $object = {};

	$object.duplicateCheck = function ($this, $transactions_without_duplicates) {
		var $duplicate_counter = 0;
		$($transactions_without_duplicates).each(function () {
			if ($this.description === this.description && $this.merchant === this.merchant && $this.total === this.total && $this.type === this.type && $this.account === this.account) {
				//it is a duplicate
				$duplicate_counter += 1;
			}
		});
		if ($duplicate_counter === 0) {
			var $allocated_percent = $this.tags.allocated_percent;
			var $allocated_fixed = null;
			var $amount = $this.tags.amount;

			if ($allocated_percent === null) {
				$allocated_fixed = $amount;
			}
			$transactions_without_duplicates.push($this);
		}
	};
	$object.removeDuplicates = function ($transactions) {
		//for the transaction autocomplete
		for (var i = 0; i < $transactions.length; i++) {
			var $transaction = $transactions[i];
			var $id = $transaction.id;
			var $description = $transaction.description;
			var $merchant = $transaction.merchant;
			var $total = $transaction.total;
			var $type = $transaction.type;
			var $account = $transaction.account;
			var $from_account = $transaction.from_account;
			var $to_account = $transaction.to_account;

			var $object_1;

			if ($type === 'transfer') {
				$object_1 = {
					description: $description,
					total: $total,
					from_account: $from_account,
					to_account: $to_account
				};
			}
			else {
				$object_1 = {
					description: $description,
					merchant: $merchant,
					total: $total,
					type: $type,
					account: $account
				};
			}

			//we have the properties that we don't want to be duplicates in an object. now we loop through the array again to make another object, then we can compare if the two objects are equal.
			for (var j = 0; j < $transactions.length; j++) {
				var $t = $transactions[j];
				var $index = $transactions.indexOf($t);
				var $t_id = $t.id;
				var $t_description = $t.description;
				var $t_merchant = $t.merchant;
				var $t_total = $t.total;
				var $t_type = $t.type;
				var $t_account = $t.account;
				var $t_from_account = $t.from_account;
				var $t_to_account = $t.to_account;

				var $object_2 = {};

				if ($t_id !== $id && $t_type === $type) {
					//they are the same type, and not the same transaction
					if ($type === 'transfer') {
						$object_2 = {
							description: $t_description,
							total: $t_total,
							from_account: $t_from_account,
							to_account: $t_to_account
						};
					}
					else {
						$object_2 = {
							description: $t_description,
							merchant: $t_merchant,
							total: $t_total,
							type: $t_type,
							account: $t_account
						};
					}
				}

				if (_.isEqual($object_1, $object_2)) {
					$transactions.splice($index, 1);
				}				
			}
		}
		
		return $transactions;
	};
	$object.transferTransactions = function ($transactions) {
		var $counter = 0;
		var $from_account;
		var $to_account;
		var $total;
		
		$($transactions).each(function () {
			var $index = $transactions.indexOf(this);
			if (this.type === "transfer") {
				$counter++;
				if (this.total < 0) {
					//this is a negative transfer
					$from_account = this.account;
				}
				else if (this.total > 0) {
					//this is a positive transfer
					$to_account = this.account;
					$total = this.total;
				}
				if ($counter % 2 === 1) {
					//remove every second transfer transaction from the array
					$transactions.splice($index, 1);
				}
				else if ($counter % 2 === 0) {
					//keep the first of every second transfer transaction and combine the two transfers into one
					this.from_account = $from_account;
					this.to_account = $to_account;
					this.account = {};
					//so the total is positive
					this.total = $total;
				}
			}
		});
		return $transactions;
	};
	return $object;
});
app.factory('BudgetsFactory', function ($http) {
	return {

        insert: function ($budget) {
            var $url = '/api/budgets';

            var $data = {
                type: $budget.type,
                name: $budget.name,
                amount: $budget.amount,
                starting_date: $budget.sql_starting_date
            };

            return $http.post($url, $data);
        },

		update: function ($budget) {
            var $url = $budget.path;

            var $data = {
                id: $budget.id,
                name: $budget.name,
                type: $budget.type,
                amount: $budget.amount,
                starting_date: $budget.sqlStartingDate
            };
            
            return $http.put($url, $data);
		},

        destroy: function ($budget) {
            var $url = $budget.path;

            return $http.delete($url);
        }

	};
});
app.factory('ErrorsFactory', function ($q) {
    return {

        responseError: function (response) {

            if(typeof response !== "undefined") {
                var $message;

                switch(response.status) {
                    case 503:
                        $message = 'Sorry, application under construction. Please try again later.';
                        break;
                    case 401:
                        $message = 'You are not logged in';
                        break;
                    case 422:
                        var html = "<ul>";
                        angular.forEach(response.data, function(value, key) {
                            var fieldName = key;
                            angular.forEach(value, function(value) {
                                html += '<li>'+value+'</li>';
                            });
                        });
                        html += "</ul>";
                        $message = html;
                        break;
                    default:
                        $message = response.data.error;
                        break;
                }
            }
            else {
                $message = 'There was an error';
            }

            return $message;

            //return $q.reject(rejection);
        }

    };
});
app.factory('FilterFactory', function ($http) {
    var $object = {};

    $object.resetFilter = function () {
        $object.filter = {

            total: {
                in: "",
                out: ""
            },
            types: {
                in: [],
                out: []
            },
            accounts: {
                in: [],
                out: []
            },
            single_date: {
                in: {
                    user: "",
                    sql: ""
                },
                out: {
                    user: "",
                    sql: ""
                }
            },
            from_date: {
                in: {
                    user: "",
                    sql: ""
                },
                out: {
                    user: "",
                    sql: ""
                }
            },
            to_date: {
                in: {
                    user: "",
                    sql: ""
                },
                out: {
                    user: "",
                    sql: ""
                }
            },
            description: {
                in: "",
                out: ""
            },
            merchant: {
                in: "",
                out: ""
            },
            budgets: {
                in: {
                    and: [],
                    or: []
                },
                out: []
            },
            numBudgets: {
                in: "all",
                out: ""
            },
            reconciled: "any",
            offset: 0,
            num_to_fetch: 30,
            display_from: 1,
            display_to: 30
        };
        return $object.filter;
    };

    $object.resetFilter();

    $object.formatDates = function ($filter) {
        if ($filter.single_date.in.user) {
            $filter.single_date.in.sql = Date.parse($filter.single_date.in.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.single_date.in.sql = "";
        }
        if ($filter.single_date.out.user) {
            $filter.single_date.out.sql = Date.parse($filter.single_date.out.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.single_date.out.sql = "";
        }
        if ($filter.from_date.in.user) {
            $filter.from_date.in.sql = Date.parse($filter.from_date.in.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.from_date.in.sql = "";
        }
        if ($filter.from_date.out.user) {
            $filter.from_date.out.sql = Date.parse($filter.from_date.out.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.from_date.out.sql = "";
        }
        if ($filter.to_date.in.user) {
            $filter.to_date.in.sql = Date.parse($filter.to_date.in.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.to_date.in.sql = "";
        }
        if ($filter.to_date.out.user) {
            $filter.to_date.out.sql = Date.parse($filter.to_date.out.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.to_date.out.sql = "";
        }

        return $filter;
    };

    $object.getTransactions = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/filter/transactions';

        return $http.post($url, {'filter': $filter});
    };

    $object.getBasicTotals = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/filter/basicTotals';

        return $http.post($url, {'filter': $filter});
    };

    $object.getGraphTotals = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/filter/graphTotals';

        return $http.post($url, {'filter': $filter});
    };

    return $object;
});
app.factory('NewTransactionFactory', function ($http) {
    var $object = {};

    var $defaults = {
        type: 'income',
            account_id: 1,
            date: {
            entered: 'today'
        },
        merchant: '',
            description: '',
            reconciled: false,
            multiple_budgets: false,
            budgets: []
    };

    $object.getDefaults = function ($env, $accounts) {
        //Fill in the new transaction fields if development environment
        if ($env === 'local') {
            $defaults.total = 10;
            $defaults.type = 'expense';
            $defaults.date.entered = 'today';
            $defaults.merchant = 'some merchant';
            $defaults.description = 'some description';
            $defaults.budgets = [
                {
                    id: '2',
                    name: 'business',
                    type: 'fixed'
                },
                {
                    id: '4',
                    name: 'busking',
                    type: 'flex'
                }
            ];
        }

        if ($accounts.length > 0) {
            $defaults.account_id = $accounts[0].id;
            $defaults.from_account_id = $accounts[0].id;
            $defaults.to_account_id = $accounts[0].id;
        }

        return $defaults;
    };

    $object.clearFields = function (env, me, $newTransaction) {
        if (env !== 'local') {
            $newTransaction.budgets = [];
        }

        if (me.preferences.clearFields) {
            $newTransaction.total = '';
            $newTransaction.description = '';
            $newTransaction.merchant = '';
            $newTransaction.reconciled = false;
            $newTransaction.multiple_budgets = false;
        }

        return $newTransaction;
    };

    $object.anyErrors = function ($newTransaction) {
        var $messages = [];

        if (!Date.parse($newTransaction.date.entered)) {
            $messages.push('Date is not valid');
        }
        else {
            $newTransaction.date.sql = Date.parse($newTransaction.date.entered).toString('yyyy-MM-dd');
        }

        if ($newTransaction.total === "") {
            $messages.push('Total is required');
        }
        else if (!$.isNumeric($newTransaction.total)) {
            $messages.push('Total is not a valid number');
        }

        if ($messages.length > 0) {
            return $messages;
        }

        return false;
    };

    return $object;
});
app.factory('PreferencesFactory', function ($http) {
    return {
        savePreferences: function ($preferences) {
            var $url = 'api/update/preferences';
            var $data = $preferences;

            return $http.post($url, $data);
        },
        insertOrUpdateDateFormat: function ($new_format) {
            var $url = 'api/insert/insertOrUpdateDateFormat';
            var $data = {
                new_format: $new_format
            };

            return $http.post($url, $data);
        },
        updateColors: function ($colors) {
            var $url = 'api/update/colors';
            var $description = 'colors';
            var $data = {
                description: $description,
                colors: $colors
            };

            return $http.post($url, $data);
        }
    };
});
app.factory('SavingsFactory', function ($http) {
	return {
		updateSavingsTotal: function () {
			var $amount = $("#edited-savings-total").val().replace(',', '');
			var $url = '/api/savings/set';
			var $data = {
				amount: $amount
			};
			
			return $http.put($url, $data);
		},
		addFixedToSavings: function () {
			var $amount_to_add = $("#add-fixed-to-savings").val();
			var $url = '/api/savings/increase';
			var $data = {
				amount: $amount_to_add
			};
			$("#add-fixed-to-savings").val("");
			
			return $http.put($url, $data);
		},
		addPercentageToSavings: function () {
			var $percentage_of_RB = $("#add-percentage-to-savings").val();
			var $url = '/api/savings/increase';
			var $data = {
				amount: $percentage_of_RB,
			};
			$("#add-percentage-to-savings").val("");
			
			return $http.put($url, $data);
		}
	};
});
app.factory('TotalsFactory', function ($http) {
    return {

        /**
         * Get all the totals
         * @returns {*}
         */
        getTotals: function () {
            var $url = '/api/totals';

            return $http.get($url);
        },
        getSideBarTotals: function () {
            var $url = '/api/totals/sidebar';

            return $http.get($url);
        },
        getFixedBudgetTotals: function () {
            var $url = '/api/totals/fixedBudget';

            return $http.get($url);
        },
        getFlexBudgetTotals: function () {
            var $url = '/api/totals/flexBudget';

            return $http.get($url);
        },
        getUnassignedBudgetTotals: function () {
            var $url = '/api/totals/unassignedBudget';

            return $http.get($url);
        }

    };
});
app.factory('TransactionsFactory', function ($http) {
    var $object = {};
    $object.totals = {};

    $object.insertIncomeOrExpenseTransaction = function ($newTransaction) {
        var $url = '/api/transactions';

        if ($newTransaction.type === 'expense' && $newTransaction.total > 0) {
            //transaction is an expense without the negative sign
            $newTransaction.total*= -1;
        }

        return $http.post($url, $newTransaction);
    };

    $object.insertTransferTransaction = function ($newTransaction, $direction) {
        var $url = '/api/transactions';
        var $data = $newTransaction;

        $data.direction = $direction;

        if ($direction === 'from') {
            $data.account_id = $data.from_account_id;
        }
        else if ($direction === 'to') {
            $data.account_id = $data.to_account_id;
        }

        return $http.post($url, $data);
    };

    $object.updateMassTags = function ($tag_array, $url, $tag_location) {
        var $transaction_id;

        var $tag_id_array = $tag_array.map(function (el) {
            return el.tag_id;
        });

        $tag_id_array = JSON.stringify($tag_id_array);

        $(".checked").each(function () {
            $transaction_id = $(this).closest("tbody").attr('id');
            var $url = 'api/update/massTags';
            var $description = 'mass edit tags';
            var $data = {
                description: $description,
                transaction_id: $transaction_id,
                tag_id_array: $tag_id_array
            };

            return $http.post($url, $data);
        });
    };

    $object.massEditDescription = function () {
        var $transaction_id;
        var $description = $("#mass-edit-description-input").val();
        var $info = {
            transaction_id: $transaction_id,
            description: $description
        };

        $(".checked").each(function () {
            $transaction_id = $(this).closest("tbody").attr('id');

            var $url = 'api/update/massDescription';
            var $data = {
                info: $info
            };

            return $http.post($url, $data);
        });
    };

    $object.updateTransaction = function ($transaction) {
        var $url = $transaction.path;

        $transaction.date = Date.parse($("#edit-transaction-date").val()).toString('yyyy-MM-dd');

        //Make sure total is negative for an expense transaction
        if ($transaction.type === 'expense' && $transaction.total > 0) {
            $transaction.total = $transaction.total * -1;
        }

        return $http.put($url, $transaction);
    };

    $object.updateReconciliation = function ($transaction) {
        var $url = $transaction.path;
        //So the reconciled value doesn't change the checkbox for the front-end
        var $data = {reconciled: 0};

        if ($transaction.reconciled) {
            $data.reconciled = 1;
        }

        return $http.put($url, $data);
    };

    $object.deleteTransaction = function ($transaction) {
        var $url = $transaction.path;

        return $http.delete($url);
    };

    $object.massDelete = function () {
        $(".checked").each(function () {
            deleteTransaction($(this));
        });
    };

    $object.getAllocationTotals = function ($transaction_id) {
        var $url = 'api/select/allocationTotals';
        var $data = {
            transaction_id: $transaction_id
        };

        return $http.post($url, $data);
    };

    $object.updateAllocation = function ($type, $value, $transaction_id, $budget_id) {
        var $url = 'api/updateAllocation';
        var $data = {
            type: $type,
            value: $value,
            transaction_id: $transaction_id,
            budget_id: $budget_id
        };

        return $http.post($url, $data);
    };

    $object.updateAllocationStatus = function ($transaction) {
        var $url = $transaction.path;
        var $data = {
            allocated: $transaction.allocated
        };

        return $http.put($url, $data);
    };


    return $object;
});

app.factory('UsersFactory', function ($http) {
    return {
        deleteAccount: function ($user) {
            var $url = $user.path;

            return $http.delete($url);
        }

    };
});
//# sourceMappingURL=factories.js.map