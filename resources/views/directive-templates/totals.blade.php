
<script type="text/ng-template" id="totals-template">

    <div ng-show="show.basic_totals || show.budget_totals" class="col-sm-2">
        @include('directive-templates.totals.remaining-balance')
        @include('directive-templates.totals.other')
    </div>

</script>

