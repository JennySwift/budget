
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">tags</h4>

    <div class="content">

        <div ng-show="filterTab === 'show'" class="group">

            <tag-autocomplete-directive
                    chosenTags="filter.tags.in"
                    dropdown="filter.dropdown"
                    tags="tags"
                    fnOnEnter="multiSearch()"
                    multipleTags="true">
            </tag-autocomplete-directive>

            <span class="input-group-btn">
                <button ng-click="clearFilterField('tags', 'in')" class="clear-search-button">clear</button>
            </span>

        </div>

        <div ng-show="filterTab === 'hide'" class="group">

            <tag-autocomplete-directive
                    chosenTags="filter.tags.out"
                    dropdown="filter.dropdown"
                    tags="tags"
                    fnOnEnter="multiSearch()"
                    multipleTags="true">
            </tag-autocomplete-directive>

            <span class="input-group-btn">
                <button ng-click="clearFilterField('tags', 'out')" class="clear-search-button">clear</button>
            </span>

        </div>

    </div>

</div>
