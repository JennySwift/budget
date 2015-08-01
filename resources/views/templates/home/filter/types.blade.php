
<div filter-dropdowns-directive
     ng-mouseleave="hideContent($event)"
     types="types"
     class="section">

    <h4 ng-mouseover="showContent($event)" class="center">types</h4>

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
                type="checkbox">
            <label for="">[[type]]</label>
        </div>

    </div>

</div>



