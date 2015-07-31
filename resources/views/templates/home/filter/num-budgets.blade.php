
<div filter-dropdowns-directive
     ng-mouseleave="hideContent($event)"
     class="section">

    <h4 ng-mouseover="showContent($event)" class="center">Budget</h4>

    <div class="content budget">

        <div>
            <input ng-model="filter.budget" type="radio" name="budget" value="all">
            <label for="">All</label>
        </div>

        <div>
            <input ng-model="filter.budget" type="radio" name="budget" value="none">
            <label for="">None</label>
        </div>

        <div>
            <input ng-model="filter.budget" type="radio" name="budget" value="single">
            <label for="">Single</label>
        </div>

        <div>
            <input ng-model="filter.budget" type="radio" name="status" value="multiple">
            <label for="">Multiple</label>
        </div>

    </div>

</div>