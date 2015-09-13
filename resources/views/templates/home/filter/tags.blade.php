
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">tags</h4>

    <div class="content">
        <div ng-show="filterTab === 'show'">
            <div>Transactions will contain all of the tags entered here</div>

            <div class="group">

                <tag-autocomplete-directive
                        chosenTags="filter.budgets.in.and"
                        dropdown="filter.dropdown.in.and"
                        tags="budgets"
                        fnOnEnter="filterTransactions()"
                        multipleTags="true">
                </tag-autocomplete-directive>

                <span class="input-group-btn">
                    <button ng-click="clearTagField('in', 'and')" class="clear-search-button">clear</button>
                </span>

            </div>

            <div>Transactions will contain at least one of the tags entered here</div>

            <div class="group">

                <tag-autocomplete-directive
                        chosenTags="filter.budgets.in.or"
                        dropdown="filter.dropdown.in.or"
                        tags="budgets"
                        fnOnEnter="filterTransactions()"
                        multipleTags="true">
                </tag-autocomplete-directive>

                <span class="input-group-btn">
                    <button ng-click="clearTagField('in', 'or')" class="clear-search-button">clear</button>
                </span>

            </div>

        </div>

        <div ng-show="filterTab === 'hide'">
            <div>Transactions will contain none of the budgets entered here</div>

            <div class="group">

                <tag-autocomplete-directive
                        chosenTags="filter.budgets.out"
                        dropdown="filter.dropdown"
                        tags="tags"
                        fnOnEnter="filterTransactions()"
                        multipleTags="true">
                </tag-autocomplete-directive>

            <span class="input-group-btn">
                <button ng-click="clearTagField('out')" class="clear-search-button">clear</button>
            </span>

            </div>
        </div>

    </div>

</div>
