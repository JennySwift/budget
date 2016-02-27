var EditBudgetPopup = Vue.component('edit-budget-popup', {
    template: '#edit-budget-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedBudget: {}
        };
    },
    components: {},
    methods: {

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});
