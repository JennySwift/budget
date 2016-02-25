<div class="main-content">

    <side-bar-totals-directive
            v-show="show.basic_totals || show.budget_totals"
            show="show">
    </side-bar-totals-directive>

    <div v-controller="TransactionsController" v-show="tab === 'transactions'" class="flex-grow-2">
        @include('templates.home.popups.index')
        @include('templates.home.transactions')
    </div>

    <div v-show="tab === 'graphs'" class="flex-grow-2">
        <graphs-directive></graphs-directive>
    </div>

    <div v-controller="FilterController">
        @include('main.shared.filter')
    </div>

</div>