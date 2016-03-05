<script id="saved-filters-template" type="x-template">

    <div class="form-group">
        <label for="saved-filter">Saved Filters</label>

        <select
                v-model="savedFilter"
                v-on:change="chooseSavedFilter(savedFilter)"
                id="saved-filter"
                class="form-control"
        >
            <option
                    v-for="savedFilter in savedFilters"
                    v-bind:value="savedFilter"
            >
                @{{ savedFilter.name }}
            </option>
        </select>
    </div>

</script>