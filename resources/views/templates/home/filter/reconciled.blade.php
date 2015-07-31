

<div filter-dropdowns-directive
     ng-mouseleave="hideContent($event)"
     class="section">

    <h4 ng-mouseover="showContent($event)" class="center">reconciled</h4>

    <div class="content status">

        <div>
            <input ng-model="filter.reconciled" type="radio" name="status" value="any">
            <label for="">Any</label>
        </div>

        <div>
            <input ng-model="filter.reconciled" type="radio" name="status" value="true">
            <label for="">Reconciled</label>
        </div>

        <div>
            <input ng-model="filter.reconciled" type="radio" name="status" value="false">
            <label for="">Unreconciled</label>
        </div>

    </div>

</div>
