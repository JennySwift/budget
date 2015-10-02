app.factory('ShowFactory', function () {
    return {
        defaults: {
            newBudget: false,
            popups: {},
            allocationPopup: false,
            actions: false,
            status: false,
            date: true,
            description: true,
            merchant: true,
            total: true,
            type: true,
            account: true,
            reconciled: true,
            tags: true,
            dlt: true,
            //components
            new_transaction: true,
            basic_totals: true,
            budget_totals: true,
            filter_totals: true,
            edit_transaction: false,
            edit_tag: false,
            budget: false,
            filter: false,
            autocomplete: {
                description: false,
                merchant: false
            },
            savings_total: {
                input: false,
                edit_btn: true
            }

        }

    };
});