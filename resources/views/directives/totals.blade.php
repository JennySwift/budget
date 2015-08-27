
<div ng-show="show.basic_totals || show.budget_totals" class="col-sm-2">
    <!-- basic totals -->
    <button ng-click="clearChanges()" class="btn btn-info btn-xs">clear changes</button>

    @include('directives.totals.basic')
    @include('directives.totals.budget')

</div>
