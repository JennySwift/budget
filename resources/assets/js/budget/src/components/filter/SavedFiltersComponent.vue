<template>
    <div class="form-group">
        {{--<label for="saved-filter">Saved Filters</label>--}}



        <autocomplete
            input-label="Saved Filters"
            id-to-focus-after-autocomplete=""
            autocomplete-field-id="saved-filter"
            :unfiltered-autocomplete-options="savedFilters"
            prop="name"
            label-for-option=""
            :function-on-enter="chooseSavedFilter"
            :function-when-option-is-chosen="chooseSavedFilter"
            :model.sync="selectedSavedFilter"
            :delete-function="deleteSavedFilter"
        >
        </autocomplete>

        {{--<select--}}
        {{--v-model="selectedSavedFilter"--}}
        {{--v-on:change="chooseSavedFilter()"--}}
        {{--id="saved-filter"--}}
        {{--class="form-control"--}}
        {{-->--}}
        {{--<option--}}
        {{--v-for="savedFilter in savedFilters"--}}
        {{--v-bind:value="savedFilter"--}}
        {{-->--}}
        {{--@{{ savedFilter.name }}--}}
        {{--<button class="btn btn-xs btn-danger">Delete</button>--}}
        {{--</option>--}}
        {{--</select>--}}
    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                savedFiltersRepository: SavedFiltersRepository.state,
                selectedSavedFilter: {},
            };
        },
        components: {},
        computed: {
            savedFilters: function () {
                return this.savedFiltersRepository.savedFilters;
            }
        },
        methods: {

            /**
             *
             */
            chooseSavedFilter: function () {
                FilterRepository.setFields(this.selectedSavedFilter.filter);
                this.runFilter();
            },

            /**
             *
             */
            deleteSavedFilter: function (savedFilter) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/savedFilters/' + savedFilter.id, function (response) {
                    SavedFiltersRepository.deleteSavedFilter(this.selectedSavedFilter);
                    $.event.trigger('provide-feedback', ['SavedFilter deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                    .error(function (data, status, response) {
                        HelpersRepository.handleResponseError(data, status, response);
                    });
            }
        },
        props: [
            'runFilter'
        ],
        mounted: function () {

        }
    }
</script>
