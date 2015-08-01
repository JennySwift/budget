
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">amount</h4>

    <div class="content">
        <div class="group">
            <input
                ng-model="filter.total"
                ng-keyup="filterTotal($event.keyCode)"
                type="text"
                placeholder="amount">

        <span class="input-group-btn">
            <button ng-click="clearFilterField('total')" class="clear-search-button" type="button">clear</button>
        </span>
        </div>
    </div>

</div>
