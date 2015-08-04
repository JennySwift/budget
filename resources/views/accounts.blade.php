
@extends('layouts.master')

@section('controller', 'AccountsController')

@section('page-content')

    <div id="accounts">

        @include('templates.popups.settings.index')

        <input
            ng-keyup="insertAccount($event.keyCode)"
            type="text"
            class="new_account_input font-size-sm center margin-bottom"
            id="new_account_input"
            placeholder="new account">

        <table class="table table-bordered">
            <tr ng-repeat="account in accounts">
                <td>[[account.name]]</td>
                <td>
                    <button
                        ng-click="showEditAccountPopup(account.id, account.name)">
                        edit
                    </button>
                </td>
                <td>
                    <button
                        ng-click="deleteAccount(account.id)"
                        class="btn btn-default">
                        delete
                    </button>
                </td>
            </tr>
        </table>
    </div>

@stop