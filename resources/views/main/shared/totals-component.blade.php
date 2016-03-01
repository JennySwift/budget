<script id="totals-template" type="x-template">

<div>

    <div v-show="show.basicTotals || show.budgetTotals" class="col-sm-2">
        @include('main.shared.totals.remaining-balance')
        @include('main.shared.totals.other')
    </div>
</div>

</script>

