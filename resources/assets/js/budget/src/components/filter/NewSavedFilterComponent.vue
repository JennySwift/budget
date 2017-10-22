<template>
    <button
        v-on:click="insertSavedFilter()"
        class="btn btn-default">
        Save filter
    </button>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        methods: {

            /**
             *
             */
            insertSavedFilter: function () {
                var name = prompt('Please name your filter');

                var data = {
                    name: name,
                    filter: this.shared.filter
                };

                helpers.post({
                    url: '/api/savedFilters',
                    data: data,
                    message: 'Filter saved',
                    callback: function (response) {
                        store.addSavedFilter(response.data);
                    }.bind(this)
                });
            },

        },
        props: [],
        mounted: function () {

        }
    }
</script>