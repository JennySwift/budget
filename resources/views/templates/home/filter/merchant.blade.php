
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">merchant</h4>

    <div class="content">

        <div class="group">
            <input
                ng-show="filterTab === 'show'"
                ng-model="filter.merchant.in"
                ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                type="text"
                placeholder="merchant">

            <input
                ng-show="filterTab === 'hide'"
                ng-model="filter.merchant.out"
                ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                type="text"
                placeholder="merchant">

            <span class="input-group-btn">
                <button
                    ng-click="clearFilterField('merchant')"
                    class="clear-search-button">
                    clear
                </button>
            </span>

        </div>

    </div>

</div>