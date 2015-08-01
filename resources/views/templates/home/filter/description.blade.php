
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">description</h4>

    <div class="content">

        <div class="group">
            <input
                ng-model="filter.description"
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




