var HomePage = Vue.component('home-page', {
    template: '#home-page-template',
    data: function () {
        return {
            page: 'home',
            budgets: budgets,
            colors: me.preferences.colors,
            transactions: transactions,
            tab: ''
        };
    },
    components: {},
    methods: {
        toggleFilter: function () {
            $scope.show.filter = !$scope.show.filter;
        },

        transactionsTab: function () {
            $scope.tab = 'transactions';
            $scope.show.basic_totals = true;
            $scope.show.budget_totals = true;
            $scope.show.filter = false;
            $rootScope.$emit('runFilter');
        },

        graphsTab: function () {
            $scope.tab = 'graphs';
            $scope.show.basic_totals = false;
            $scope.show.budget_totals = false;
            $scope.show.filter = true;
            $rootScope.$emit('runFilter');
        },

        setTab: function () {
            if (env === 'local') {
                this.tab = 'transactions';
            }
            else {
                this.tab = 'transactions';
            }
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.setTab();
    }
});