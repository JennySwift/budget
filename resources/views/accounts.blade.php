
@extends('layouts.master')

@section('controller', 'AccountsController')

@section('page-content')
    @include('templates.accounts.popups.index')

    <div id="accounts">

        <div class="create-new-account">
            <label>Create a new account</label>

            <div class="flex">

                <input
                    ng-keyup="insertAccount($event.keyCode)"
                    type="text"
                    class="new_account_input font-size-sm center margin-bottom"
                    id="new_account_input"
                    placeholder="new account"
                    name="name">

                <div>
                    <button ng-click="insertAccount(13)" class="btn btn-success">Create</button>
                </div>

            </div>

        </div>

        <table class="">
            <tr ng-repeat="account in accounts">
                <td
                    ng-click="showEditAccountPopup(account.id, account.name)"
                    class="pointer">
                    [[account.name]]
                </td>

                <td>
                    <button
                        ng-click="deleteAccount(account)"
                        class="btn btn-default btn-danger btn-sm">
                        delete
                    </button>
                </td>
            </tr>
        </table>
    </div>

@stop