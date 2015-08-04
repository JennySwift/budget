
@extends('layouts.master')

@section('controller', 'BudgetsController')
@section('id', 'budget')

@section('page-content')

    @include('templates.popups.budget/index')

    <totals-directive
            totals="totals"
            getTotals="getTotals()">
    </totals-directive>

    @include('templates.feedback')

    <div>

        {{--<div class="margin-bottom">--}}
        {{--<input ng-keyup="addFixedToSavings($event.keyCode)" type="text" placeholder="add fixed amount to savings" id="add-fixed-to-savings">--}}
        {{--<input ng-keyup="addPercentageToSavings($event.keyCode)" type="text" placeholder="add percentage of RB to savings" id="add-percentage-to-savings">--}}
        {{--</div>--}}

        @include('templates.budgets.help')
        @include('templates.budgets.fixed-budget-inputs')
        @include('templates.budgets.fixed-budget-table')
        @include('templates.budgets.flex-budget-inputs')
        @include('templates.budgets.flex-budget-table')

    </div>

    <span id="budget_hover_span" class="tooltipster" title=""></span>

@stop