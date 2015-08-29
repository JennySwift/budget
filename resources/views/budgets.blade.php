
@extends('layouts.master')

@section('controller', 'BudgetsController')
@section('id', 'budget')

@section('page-content')

    @include('templates.popups.budget/index')

    <div id="budget-toolbar">
        <div class="btn-group">
            <button
                ng-click="tab = 'fixed'"
                ng-class="{'selected': tab === 'fixed'}"
                class="btn">Fixed Budgets
            </button>
            <button
                ng-click="tab = 'flex'"
                ng-class="{'selected': tab === 'flex'}"
                class="btn">
                Flex Budgets
            </button>
        </div>
        @include('templates.budgets.help')
    </div>

    <div id="budget-content">
        <totals-directive
                {{--totals="totals"--}}
                basictotals="basicTotals"
                getTotals="getTotals()"
                show="show">
        </totals-directive>

        @include('templates.feedback')

        <div>

            {{--<div class="margin-bottom">--}}
            {{--<input ng-keyup="addFixedToSavings($event.keyCode)" type="text" placeholder="add fixed amount to savings" id="add-fixed-to-savings">--}}
            {{--<input ng-keyup="addPercentageToSavings($event.keyCode)" type="text" placeholder="add percentage of RB to savings" id="add-percentage-to-savings">--}}
            {{--</div>--}}

            <div ng-show="tab === 'fixed'">
                @include('templates.budgets.fixed-budget-inputs')
                @include('templates.budgets.fixed-budget-table')
            </div>

            <div ng-show="tab === 'flex'">
                @include('templates.budgets.flex-budget-inputs')
                @include('templates.budgets.flex-budget-table')
            </div>

        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>

@stop