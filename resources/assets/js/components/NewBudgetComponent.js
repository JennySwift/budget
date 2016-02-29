var NewBudget = Vue.component('new-budget', {
    template: '#new-budget-template',
    data: function () {
        return {
            showNewBudget: false,
            newBudget: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('toggle-new-budget', function (event) {
                that.showNewBudget = !that.showNewBudget;
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});
