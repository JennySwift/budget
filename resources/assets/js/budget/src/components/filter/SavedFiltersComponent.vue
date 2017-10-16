<template>
    <div class="form-group">
        <!--<label for="saved-filter">Saved Filters</label>-->



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

        <!--<select-->
        <!--v-model="selectedSavedFilter"-->
        <!--v-on:change="chooseSavedFilter()"-->
        <!--id="saved-filter"-->
        <!--class="form-control"-->
        <!-->-->
        <!--<option-->
        <!--v-for="savedFilter in savedFilters"-->
        <!--v-bind:value="savedFilter"-->
        <!-->-->
        <!--{{ savedFilter.name }}-->
        <!--<button class="btn btn-xs btn-danger">Delete</button>-->
        <!--</option>-->
        <!--</select>-->
    </div>

</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    export default {
        data: function () {
            return {
                selectedSavedFilter: {},
                shared: store.state
            };
        },
        components: {},
        computed: {
            savedFilters: function () {
                return this.shared.savedFilters;
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
                helpers.delete({
                    url: '/api/savedFilters/' + savedFilter.id,
                    array: 'savedFilters',
                    itemToDelete: this.savedFilter,
                    message: 'Saved filter deleted',
                    redirectTo: this.redirectTo,
                    callback: function () {
                        store.deleteSavedFilter(this.selectedSavedFilter);
                    }.bind(this)
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
