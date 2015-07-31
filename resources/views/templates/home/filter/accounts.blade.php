
<div filter-dropdowns-directive
     ng-mouseleave="hideContent($event)"
     class="section">

    <h4 ng-mouseover="showContent($event)" class="center">accounts</h4>

    <div class="accounts content">

        <div>
            <input type="checkbox">
            <label for="">all</label>
        </div>

        <div>
            <input type="checkbox">
            <label for="">none</label>
        </div>

        <div ng-repeat="account in accounts">
            <input checklist-model="filter.accounts" checklist-value="account.id" type="checkbox">
            <label for="">[[account.name]]</label>
        </div>

    </div>

</div>
