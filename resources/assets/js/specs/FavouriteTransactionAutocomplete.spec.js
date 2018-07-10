var expect = require('chai').expect;
var assert = require('chai').assert;
import Vue from 'vue'
import NewTransactionComponent from '../../../../budget/src/components/new_transaction/NewTransactionComponent.vue'
import FavouritesComponent from '../../../../budget/src/components/new_transaction/FavouritesComponent.vue'
import helpers from '../../../../budget/src/repositories/Helpers'

import store from '../../../src/repositories/Store'
window.store = store;
global._ = require('lodash');

var vm = new Vue(NewTransactionComponent);
var newTransaction = vm.shared.newTransaction;

describe('favourite-transaction-autocomplete', function () {
    it('has the correct default data for a new transaction', function () {
        console.log(newTransaction);
        //Check the initial data
        assert.equal('today', newTransaction.userDate);
        assert.equal('expense', newTransaction.type);
        assert.deepEqual({}, newTransaction.account);
        assert.equal('', newTransaction.duration);
        assert.equal('', newTransaction.total);
        assert.equal('', newTransaction.merchant);
        assert.equal('', newTransaction.description);
        assert.equal(false, newTransaction.reconciled);
        assert.equal(false, newTransaction.multipleBudgets);
        assert.deepEqual([], newTransaction.budgets);
    });

    it('fills the fields for the new transaction', function () {
        var vm = new Vue(FavouritesComponent);
        vm.fillFields({
            description: 'abc',
            merchant: 'koala',
            total: '25',
            type: 'income',
            budgets: [],
            account: {id: 1, name: 'business'}
        });

        assert.equal('abc', newTransaction.description);
        assert.equal('koala', newTransaction.merchant);
        assert.equal('25', newTransaction.total);
        assert.equal('income', newTransaction.type);
        assert.deepEqual([], newTransaction.budgets);
        assert.deepEqual({id: 1, name: 'business'}, newTransaction.account);
    });
});