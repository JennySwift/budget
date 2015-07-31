
<div ng-cloak ng-show="show.filter" id="filter" class="margin-bottom">

    <div class="btn-group">
        <button ng-click="filterTab = 'show'" class="btn btn-success">Show</button>
        <button ng-click="filterTab = 'hide'" class="btn btn-danger">Hide</button>
    </div>

    <div ng-show="filterTab === 'show'">
        @include('templates/home/filter/accounts')
        @include('templates/home/filter/types')
        @include('templates/home/filter/description')
        @include('templates/home/filter/merchant')
        @include('templates/home/filter/tags')
        @include('templates/home/filter/date')
        @include('templates/home/filter/amount')
        @include('templates/home/filter/reconciled')
        @include('templates/home/filter/num-budgets')
    </div>

    <div ng-show="filterTab === 'hide'">
        nothing here yet
    </div>




</div>    