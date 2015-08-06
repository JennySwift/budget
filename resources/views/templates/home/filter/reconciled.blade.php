

<div filter-dropdowns-directive
     ng-show="filterTab === 'show'"
     class="section">

    <h4 class="center">reconciled</h4>

    <div class="content status">

        <div>
            <input
                ng-model="filter.reconciled"
                ng-change="multiSearch()"
                type="radio"
                name="status"
                value="any">
            <label for="">Any</label>
        </div>

        <div>
            <input
                ng-model="filter.reconciled"
                ng-change="multiSearch()"
                type="radio"
                name="status"
                value="true">
            <label for="">Reconciled</label>
        </div>

        <div>
            <input
                ng-model="filter.reconciled"
                ng-change="multiSearch()"
                type="radio"
                name="status"
                value="false">
            <label for="">Unreconciled</label>
        </div>

    </div>

</div>
