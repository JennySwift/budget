
@extends('layouts.master')

@section('controller', 'BudgetsController')
@section('id', 'budget')

@section('page-content')

    <div ng-controller="FlexBudgetsController">
        @include('templates.budgets.popups.index')

        @include('templates.budgets.toolbar')

        @include('templates.budgets.new-budget')

        <div id="budget-content">

            @include('templates.budgets.totals')

            <div class="budget-table">
                @include('templates.budgets.flex-budget-table')
            </div>

            <span id="budget_hover_span" class="tooltipster" title=""></span>
        </div>
    </div>

@stop