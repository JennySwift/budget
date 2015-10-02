<script type="text/ng-template" id="filter-description-template">

    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">description</h4>

        <div class="content">

            <div class="group" ng-show="filterTab === 'show'">
                <input
                        ng-model="filter.description.in"
                        ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="description">

            <span class="input-group-btn">
                <button
                        ng-click="clearFilterField('description', 'in')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div class="group" ng-show="filterTab === 'hide'">
                <input
                        ng-model="filter.description.out"
                        ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="description">

            <span class="input-group-btn">
                <button
                        ng-click="clearFilterField('description', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>
            </div>


        </div>

    </div>

</script>