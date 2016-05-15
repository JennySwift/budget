<script id="totals-template" type="x-template">

<div v-show="show.basicTotals || show.budgetTotals" id="totals">
    @include('main.shared.totals.remaining-balance')
</div>

</script>

