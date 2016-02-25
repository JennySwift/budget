
@extends('layouts.master')

@section('controller', 'FavouriteTransactionsController')

@section('page-content')

    <div id="favourite-transactions-page">


        <div id="new-favourite">
            <h2>Create a new favourite transaction</h2>

            <div>
                <label>Name your new favourite transaction</label>
                <input v-model="newFavourite.name" type="text" placeholder="name"/>
            </div>

            <div>
                <label>Type</label>
                <select v-model="newFavourite.type" class="form-control">
                    <option value="income">Credit</option>
                    <option value="expense">Debit</option>
                    {{--<option value="transfer">Transfer</option>--}}
                </select>
            </div>

            <div>
                <label>Description</label>
                <input v-model="newFavourite.description" type="text" placeholder="description"/>
            </div>

            <div>
                <label>Merchant</label>
                <input v-model="newFavourite.merchant" type="text" placeholder="merchant"/>
            </div>

            <div>
                <label>Total</label>
                <input v-model="newFavourite.total" type="text" placeholder="total"/>
            </div>

            <div>
                <label>Account</label>
                <select
                        v-options="account.id as account.name for account in accounts"
                        v-model="newFavourite.account_id"
                        class="form-control">
                </select>
            </div>

            <tag-autocomplete-directive
                    chosenTags="newFavourite.budgets"
                    dropdown="newFavourite.dropdown"
                    tags="budgets"
                    multipleTags="true">
            </tag-autocomplete-directive>

            <div>
                <button
                        v-on:click="insertFavouriteTransaction()"
                        class="btn btn-success">
                    Add new favourite
                </button>
            </div>

        </div>

        <div>
            <h2>Favourite transactions</h2>

            <table id="favourite-transactions" >
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Merchant</th>
                    <th>Total</th>
                    <th>Account</th>
                    <th>Tags</th>
                    <th></th>
                </tr>
                
                <tr v-for="favourite in favouriteTransactions">
                    <td>[[favourite.name]]</td>
                    <td>[[favourite.type]]</td>
                    <td>[[favourite.description]]</td>
                    <td>[[favourite.merchant]]</td>
                    <td>[[favourite.total]]</td>
                    <td>[[favourite.account.name]]</td>
                    <td>
                        <span v-for="budget in favourite.budgets" class="badge">[[budget.name]]</span>
                    </td>
                    <td><button v-on:click="deleteFavouriteTransaction(favourite)" class="btn-xs btn-danger">Delete</button></td>

                </tr>
            </table>

        </div>




    </div>




@stop