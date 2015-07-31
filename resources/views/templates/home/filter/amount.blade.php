
<div filter-dropdowns-directive
     ng-mouseleave="hideContent($event)"
     class="section">

    <h4 ng-mouseover="showContent($event)" class="center">amount</h4>

    <div class="content">
        <div class="group">
            <input ng-model="filter.total" type="text" placeholder="amount">
        <span class="input-group-btn">
            <button ng-click="clearFilterField('total')" class="clear-search-button" type="button">clear</button>
        </span>
        </div>
    </div>

</div>
