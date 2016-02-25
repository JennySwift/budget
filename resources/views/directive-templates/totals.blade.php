
<script type="text/v-template" id="totals-template">

    <div v-show="show.basic_totals || show.budget_totals" class="col-sm-2">
        @include('directive-templates.totals.remaining-balance')
        @include('directive-templates.totals.other')
    </div>

</script>

