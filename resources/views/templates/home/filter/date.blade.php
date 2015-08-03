
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">date</h4>

    <div class="content">

        <div class="group">

            <input
                ng-model="filter.single_date"
                ng-keyup="filterDate($event.keyCode, filter.single_date)"
                type="text"
                placeholder="single date">

            <span class="input-group-btn">
                <button
                    ng-click="clearFilterField('single_date')"
                    class="clear-search-button">
                    clear
                </button>
            </span>

        </div>

        <div class="group">

            <input
                ng-model="filter.from_date"
                ng-keyup="filterDate($event.keyCode, filter.single_date)"
                type="text"
                placeholder="from a date">

            <span class="input-group-btn">
                <button
                    ng-click="clearFilterField('from_date')"
                    class="clear-search-button"
                    type="button">
                    clear
                </button>
            </span>

        </div>

        <div class="group">

            <input
                ng-model="filter.to_date"
                ng-keyup="filterDate($event.keyCode)"
                type="text"
                placeholder="to a date">

            <span class="input-group-btn">
                <button
                    ng-click="clearFilterField('to_date')"
                    class="clear-search-button"
                    type="button">
                    clear
                </button>
            </span>

        </div>

    </div>

</div>