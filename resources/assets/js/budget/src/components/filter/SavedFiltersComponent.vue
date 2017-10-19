<template>
    <div class="form-group">
        <autocomplete
            input-label="Saved Filters"
            id-to-focus-after-autocomplete=""
            input-id="saved-filter"
            :unfiltered-options="shared.savedFilters"
            prop="name"
            label-for-option=""
            :function-on-enter="chooseSavedFilter"
            :selected.sync="selectedSavedFilter"
            :delete-function="deleteSavedFilter"
        >
        </autocomplete>
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
