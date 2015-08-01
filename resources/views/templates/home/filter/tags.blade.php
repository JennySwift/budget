
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">tags</h4>

    <div class="content">
        <div class="group">

            <tag-autocomplete-directive
                    chosenTags="filter.tags"
                    dropdown="filter.dropdown"
                    tags="tags"
                    fnOnEnter="insertTransaction(13)"
                    multipleTags="true">
            </tag-autocomplete-directive>

        <span class="input-group-btn">
            <button ng-click="clearFilterField('tags')" class="clear-search-button">clear</button>
        </span>

        </div>
    </div>

</div>
