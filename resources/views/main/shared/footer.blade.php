
@include('main.shared.real-footer')

{{--Pages--}}
@include('main.home-page-component')
@include('main.help.help-page-component')
@include('main.accounts.accounts-page-component')
@include('main.preferences-page-component')
@include('main.favourite-transactions-page-component')
@include('main.budgets.fixed-budgets-page-component')
@include('main.shared.totals-component')
@include('main.budgets.flex-budgets-page-component')
@include('main.budgets.unassigned-budgets-page-component')

{{--Budgets--}}
@include('main.budgets.edit-budget-popup-component')
@include('main.budgets.new-budget-component')

{{--Accounts--}}
@include('main.accounts.new-account-component')
@include('main.accounts.edit-account-component')

{{--Favourite transactions--}}
@include('main.new-favourite-transaction-component')

@include('main.new-transaction-component')
@include('main.shared.navbar-component')
@include('main.shared.budget-autocomplete-component')
@include('main.shared.checkbox-component')
@include('main.shared.feedback-component')
@include('main.shared.loading-component')
@include('main.transaction-autocomplete-component')
@include('main.transactions-component')
@include('main.transaction-component')
@include('main.filter.filter-component')

{{--Filters--}}
@include('main.filter.filter-component')
@include('main.filter.graphs-component')
@include('main.filter.accounts-filter-component')
@include('main.filter.types-filter-component')
@include('main.filter.totals-filter-component')
@include('main.filter.descriptions-filter-component')
@include('main.filter.merchants-filter-component')
@include('main.filter.budgets-filter-component')
@include('main.filter.dates-filter-component')
@include('main.filter.num-budgets-filter-component')
@include('main.filter.saved-filters-component')
@include('main.filter.new-saved-filter-component')
@include('main.filter.reconciled-filter-component')
@include('main.filter.totals-for-filter-component')
@include('main.filter.toolbar-for-filter-component')
@include('main.filter.mass-transaction-update-popup-component')

{{--Transactions--}}
@include('main.home.edit-transaction-popup-component')
@include('main.home.allocation-popup-component')
@include('main.home.budget-allocation-component')

<script type="text/javascript" src="/js/all.js"></script>

</body>
</html>
