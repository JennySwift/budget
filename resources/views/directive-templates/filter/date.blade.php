<script type="text/ng-template" id="filter-date-template">

    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">date</h4>

        <div class="content">

            <div ng-show="filterTab === 'show'" class="group">

                <input
                        ng-model="filter.single_date.in"
                        ng-keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="single date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        ng-click="clearDateField('single_date', 'in')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div ng-show="filterTab === 'hide'" class="group">

                <input
                        ng-model="filter.single_date.out"
                        ng-keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="single date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        ng-click="clearDateField('single_date', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div ng-show="filterTab === 'show'" class="group">

                <input
                        ng-model="filter.from_date.in"
                        ng-keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="from a date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        ng-click="clearDateField('from_date', 'in')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>

            </div>

            <div ng-show="filterTab === 'show'" class="group">

                <input
                        ng-model="filter.to_date.in"
                        ng-keyup="filterDate($event.keyCode)"
                        type="text"
                        placeholder="to a date"
                        formatted-date>

            <span class="input-group-btn">
                <button
                        ng-click="clearDateField('to_date', 'in')"
                        class="clear-search-button"
                        type="button">
                    clear
                </button>
            </span>

            </div>

        </div>

    </div>

</script>
