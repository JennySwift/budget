
<div filter-dropdowns-directive
     types="types"
     class="section">

    <h4 class="center">types</h4>

    <div class="types transition content">

        <div>
            <input type="checkbox">
            <label for="">all</label>
        </div>

        <div>
            <input type="checkbox">
            <label for="">none</label>
        </div>

        <div ng-repeat="type in types">
            <input
                checklist-model="filter.types"
                checklist-value="type"
                checklist-change="multiSearch()"
                type="checkbox">
            <label for="">[[type]]</label>
        </div>

    </div>

</div>



