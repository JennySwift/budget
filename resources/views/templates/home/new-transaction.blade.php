
<!-- This div is so the is has same margin as other -->
<div ng-show="show.new_transaction" ng-style="{color: colors[new_transaction.type]}" id="new-transaction">
    <div class="type">
        @include('templates.home.new-transaction.type')
    </div>

    <div>
        @include('templates.home.new-transaction.date')
        @include('templates.home.new-transaction.total')
    </div>

    <div>
        @include('templates.home.new-transaction.merchant')
        @include('templates.home.new-transaction.description')
    </div>

    <div>
        @include('templates.home.new-transaction.accounts')
        @include('templates.home.new-transaction.reconcile')
    </div>

    <div>
        @include('templates.home.new-transaction.tags')
        @include('templates.home.new-transaction.enter')
    </div>

</div>
