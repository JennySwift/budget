
<script type="text/ng-template" id="totals-template">

    <div ng-show="show.basic_totals || show.budget_totals" class="col-sm-2">
        @include('directives.totals.remaining-balance')
        @include('directives.totals.other')
    </div>

</script>

