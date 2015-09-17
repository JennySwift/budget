
<div ng-show="show.basic_totals || show.budget_totals" class="col-sm-2">
    <!-- basic totals -->

    @include('directives.totals.remaining-balance')
    @include('directives.totals.other')

</div>
