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
                    filter: this.filter
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
        props: [
            'filter'
        ],
        mounted: function () {

        }
    }
</script>