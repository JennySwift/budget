
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">accounts</h4>

    <div class="accounts content">

        <div>
            <input type="checkbox">
            <label for="">all</label>
        </div>

        <div>
            <input type="checkbox">
            <label for="">none</label>
        </div>

        <div ng-show="filterTab === 'show'" ng-repeat="account in accounts">
            <input
                    checklist-model="filter.accounts.in"
                    checklist-value="account.id"
                    checklist-change="runFilter()"
                    ng-disabled="filter.accounts.out.indexOf(account.id) !== -1"
                    type="checkbox">

            <label
                ng-class="{'disabled': filter.accounts.out.indexOf(account.id) !== -1}"
                for="">
                [[account.name]]
            </label>
        </div>

        <div ng-show="filterTab === 'hide'" ng-repeat="account in accounts">
            <input
                    checklist-change="runFilter()"
                    checklist-model="filter.accounts.out"
                    checklist-value="account.id"
                    ng-disabled="filter.accounts.in.indexOf(account.id) !== -1"
                    type="checkbox">

            <label
                    ng-class="{'disabled': filter.accounts.in.indexOf(account.id) !== -1}"
                    for="">
                [[account.name]]
            </label>
        </div>

    </div>

</div>
