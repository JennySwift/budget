
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">description</h4>

    <div class="content">

        <div class="group">
            <input
                ng-show="filterTab === 'show'"
                ng-model="filter.description.in"
                ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                type="text"
                placeholder="description">

            <input
                    ng-show="filterTab === 'hide'"
                    ng-model="filter.description.out"
                    ng-keyup="filterDescriptionOrMerchant($event.keyCode)"
                    type="text"
                    placeholder="description">

            <span class="input-group-btn">
                <button
                    ng-click="clearFilterField('description')"
                    class="clear-search-button">
                    clear
                </button>
            </span>

        </div>

    </div>

</div>




