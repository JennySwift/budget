var DatesFilter = Vue.component('dates-filter', {
    template: '#dates-filter-template',
    data: function () {
        return {
            showContent: false
        };
    },
    components: {},
    methods: {

    },
    props: [
        'filter',
        'filterTab',
        'runFilter',
        'clearFilterField'
    ],
    ready: function () {

    }
});