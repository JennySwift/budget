<script type="text/ng-template" id="filter-merchant-template">


    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">merchant</h4>

        <div class="content">

            <div class="group" ng-show="filterTab === 'show'">
                <input
                        ng-model="filter.merchant.in"
                        ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="merchant">

            <span class="input-group-btn">
                <button
                        ng-click="clearFilterField('merchant', 'in')"
                        class="clear-search-button">
                    clear
                </button>
            </span>

            </div>

            <div class="group" ng-show="filterTab === 'hide'">
                <input
                        ng-model="filter.merchant.out"
                        ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                        type="text"
                        placeholder="merchant">

            <span class="input-group-btn">
                <button
                        ng-click="clearFilterField('merchant', 'out')"
                        class="clear-search-button">
                    clear
                </button>
            </span>
            </div>

        </div>

    </div>

</script>