
@extends('layouts.master')

@section('controller', 'BudgetsController')
@section('id', 'budget')

@section('page-content')

    <div ng-controller="FixedBudgetsController">
        @include('templates.budgets.popups.index')

        @include('templates.budgets.toolbar')

        <div id="budget-content">

            @include('templates.budgets.totals')
            @include('templates.feedback')

            <div>

                @include('templates.budgets.new-budget')
                @include('templates.budgets.fixed-budget-table')

            </div>

            <span id="budget_hover_span" class="tooltipster" title=""></span>
        </div>
    </div>

@stop