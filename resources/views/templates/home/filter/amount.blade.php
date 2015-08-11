
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">amount</h4>

    <div class="content">

        <span>Negative sign required for negative numbers</span>

        <div ng-show="filterTab === 'show'" class="group">
            <input
                ng-model="filter.total.in"
                ng-keyup="filterTotal($event.keyCode)"
                type="text"
                placeholder="amount">

            <span class="input-group-btn">
                <button
                    ng-click="clearFilterField('total', 'in')"
                    class="clear-search-button"
                    type="button">
                    clear
                </button>
            </span>
        </div>

        <div ng-show="filterTab === 'hide'" class="group">
            <input
                    ng-model="filter.total.out"
                    ng-keyup="filterTotal($event.keyCode)"
                    type="text"
                    placeholder="amount">

            <span class="input-group-btn">
                <button
                        ng-click="clearFilterField('total', 'out')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>
        </div>

    </div>

</div>
