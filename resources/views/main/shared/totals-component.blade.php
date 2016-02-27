<script id="totals-template" type="x-template">

<div>
    <div v-show="showBasicTotals || showBudgetTotals" class="col-sm-2">
        @include('main.shared.totals.remaining-balance')
        {{--@include('main.shared.totals.other')--}}
    </div>
</div>

</script>

