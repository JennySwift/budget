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
        updateAccountName: function ($account_id, $account_name) {
            var $url = '/api/accounts/' + $account_id;
            var $data = { name: $account_name };

            return $http.put($url, $data);
        },
        deleteAccount: function ($account) {
            console.log($account.path);
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
				if (this.total.indexOf("-") != -1) {
					//this is a negative transfer
					$from_account = this.account;
				}
				else if (this.total.indexOf("-") == -1) {
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
            var $url = '/api/budgets/'+$budget.id;

            return $http.delete($url);
        }

	};
});
app.factory('FeedbackFactory', function ($http) {
    var $object = {};

    $object.provideFeedback = function ($message) {
        //My watch in my controller would only work once unless I made an object here.
        //(Just $object.message would not work.)
        $object.data = {
            message: $message,
            update: true
        };
        return $object.data;
    };

    return $object;
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
            num_to_fetch: 30
        };
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

    $object.filterTransactions = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/select/filter';

        return $http.post($url, {'filter': $filter});
    };

    /**
     * For displaying the filtered transactions
     * and the filter totals
     * and the non-filter totals on the page
     * todo: maybe this should be in some totals factory
     * @param $data
     */
    //$object.updateDataForControllers = function ($data) {
    //    if ($data.filter_results) {
    //        //This includes filtered transactions as well as filter totals
    //        $object.filter_results = $data.filter_results;
    //    }
    //};

    return $object;
});
//app.factory('HelpersFactory', function ($http) {
//    return {
//
//
//    };
//});
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
		},
		addPercentageToSavingsAutomatically: function ($amount_to_add) {
			var $url = '/api/savings/increase';
			var $data = {
				amount: $amount_to_add
			};
			
			return $http.put($url, $data);
		},
		reverseAutomaticInsertIntoSavings: function ($amount_to_subtract) {
			var $url = '/api/savings/decrease';
			var $data = {
				amount: $amount_to_subtract
			};
			
			return $http.put($url, $data);
		}
	};
});
app.factory('TotalsFactory', function ($http) {
    return {

        getTotals: function () {
            var $url = '/api/totals';

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

    $object.updateTransaction = function ($transaction, $filter) {
        var $url = $transaction.path;

        //Make sure total is negative for an expense transaction
        if ($transaction.type === 'expense' && $transaction.total > 0) {
            $transaction.total = $transaction.total * -1;
        }

        var $data = {
            transaction: $transaction,
            filter: $filter
        };

        return $http.put($url, $data);
    };

    $object.updateReconciliation = function ($transaction_id, $reconciled, $filter) {
        var $url = 'api/updateReconciliation';

        if ($reconciled === true) {
            $reconciled = 'true';
        }
        else {
            $reconciled = 'false';
        }

        var $data = {
            id: $transaction_id,
            reconciled: $reconciled,
            filter: $filter
        };

        return $http.post($url, $data);
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

    $object.countTransactionsWithBudget = function ($budget) {
        var $url = '/api/select/countTransactionsWithBudget';

        var $data = {
            budget_id: $budget.id
        };

        return $http.post($url, $data);
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

    $object.updateAllocationStatus = function ($transaction_id, $status) {
        var $url = 'api/updateAllocationStatus';
        var $data = {
            transaction_id: $transaction_id,
            status: $status
        };

        return $http.post($url, $data);
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