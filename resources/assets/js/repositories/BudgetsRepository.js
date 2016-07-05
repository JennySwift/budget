var BudgetsRepository = {

    state: {
        budgets: [],
        fixedBudgets: [],
        flexBudgets: [],
        unassignedBudgets: []
    },

    /**
     *
     */
    getBudgets: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/budgets', function (response) {
            BudgetsRepository.state.budgets = response;
            $.event.trigger('hide-loading');
        })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
    },

    /**
     *
     */
    getFixedBudgets: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/budgets?fixed=true', function (response) {
            BudgetsRepository.state.fixedBudgets = response;
            $.event.trigger('hide-loading');
        })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
    },

    /**
     *
     */
    getFlexBudgets: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/budgets?flex=true', function (response) {
            BudgetsRepository.state.flexBudgets = response;
            $.event.trigger('hide-loading');
        })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
    },

    /**
     *
     */
    getUnassignedBudgets: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/budgets?unassigned=true', function (response) {
            BudgetsRepository.state.unassignedBudgets = response;
            $.event.trigger('hide-loading');
        })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
    },

    /**
    *
    * @param budget
    */
    updateBudget: function (budget, that) {
        //Update the budgets array
        var index = HelpersRepository.findIndexById(this.state.budgets, budget.id);
        this.state.budgets.$set(index, budget);

        if (that.page !== budget.type) {
            //The budget type has changed. Remove it from the specific budgets array it was in, and add it to another specific budgets array.
            this.deleteBudgetFromSpecificArray(budget, that);
            this.addBudgetToSpecificArray(budget, that);
        }
        else {
            //The budget type has not changed. Update the specific budgets array
            switch(that.page) {
                case 'fixed':
                    index = HelpersRepository.findIndexById(this.state.fixedBudgets, budget.id);
                    this.state.fixedBudgets.$set(index, budget);
                    break;
                case 'flex':
                    index = HelpersRepository.findIndexById(this.state.flexBudgets, budget.id);
                    this.state.flexBudgets.$set(index, budget);
                    break;
                case 'unassigned':
                    index = HelpersRepository.findIndexById(this.state.unassignedBudgets, budget.id);
                    this.state.unassignedBudgets.$set(index, budget);
                    break;
            }
        }
    },

    /**
     * Remove budget from budgets array as well as from specific budgets array
     * @param budget
     * @param that
     */
    deleteBudget: function (budget, that) {
        //Remove from budgets array
        this.state.budgets = HelpersRepository.deleteById(this.state.budgets, budget.id);

        this.deleteBudgetFromSpecificArray(budget, that);
    },

    /**
     *
     * @param budget
     * @param that
     */
    deleteBudgetFromSpecificArray: function(budget, that) {
        switch(that.page) {
            case 'fixed':
                this.state.fixedBudgets = HelpersRepository.deleteById(this.state.fixedBudgets, budget.id);
                break;
            case 'flex':
                this.state.flexBudgets = HelpersRepository.deleteById(this.state.flexBudgets, budget.id);
                break;
            case 'unassigned':
                this.state.unassignedBudgets = HelpersRepository.deleteById(this.state.unassignedBudgets, budget.id);
                break;
        }
    },

    /**
     *
     * @param budget
     * @param that
     */
    addBudgetToSpecificArray: function(budget, that) {
        switch(budget.type) {
            case 'fixed':
                this.state.fixedBudgets.push(budget);
                break;
            case 'flex':
                this.state.flexBudgets.push(budget);
                break;
            case 'unassigned':
                this.state.unassignedBudgets.push(budget);
                break;
        }
    },

    /**
     * 
     * @param budgets
     * @param that
     * @returns {*}
     */
    orderBudgetsFilter: function (budgets, that) {
        switch(that.orderBy) {
            case 'name':
                budgets = _.sortBy(budgets, 'name');
                break;
            case 'spentOnOrAfterStartingDate':
                budgets = _.sortBy(budgets, 'spentOnOrAfterStartingDate');
                break;
        }

        if (that.reverseOrder) {
            budgets = budgets.reverse();
        }

        return budgets;
    }
};