
@extends('layouts.master')

@section('controller', 'FavouriteTransactionsController')

@section('page-content')

    <div id="favourite-transactions-page">


        <div id="new-favourite">
            <h2>Create a new favourite transaction</h2>

            <div>
                <label>Name your new favourite transaction</label>
                <input ng-model="newFavourite.name" type="text" placeholder="name"/>
            </div>

            <div>
                <label>Type</label>
                <select ng-model="newFavourite.type" class="form-control">
                    <option value="income">Credit</option>
                    <option value="expense">Debit</option>
                    {{--<option value="transfer">Transfer</option>--}}
                </select>
            </div>

            <div>
                <label>Description</label>
                <input ng-model="newFavourite.description" type="text" placeholder="description"/>
            </div>

            <div>
                <label>Merchant</label>
                <input ng-model="newFavourite.merchant" type="text" placeholder="merchant"/>
            </div>

            <div>
                <label>Total</label>
                <input ng-model="newFavourite.total" type="text" placeholder="total"/>
            </div>

            <div>
                <label>Account</label>
                <select
                        ng-options="account.id as account.name for account in accounts"
                        ng-model="newFavourite.account_id"
                        class="form-control">
                </select>
            </div>

            <div>
                <button
                        ng-click="insertFavouriteTransaction()"
                        class="btn btn-success">
                    Add new favourite
                </button>
            </div>

        </div>

        <div>
            <h2>Favourite transactions</h2>
            <div
                ng-repeat="favourite in favouriteTransactions"
                id="favourite-transactions">
                <span>[[favourite.name]]</span>
                <button ng-click="deleteFavouriteTransaction(favourite)" class="btn-xs btn-danger">Delete</button>
            </div>
        </div>




    </div>




@stop