var PreferencesPage = Vue.component('preferences-page', {
    template: '#preferences-page-template',
    data: function () {
        return {
            me: me,
            preferences: []
        };
    },
    components: {},
    methods: {
        savePreferences: function () {
            PreferencesFactory.savePreferences($scope.me.preferences)
                .then(function (response) {
                    $rootScope.$broadcast('provideFeedback', 'Preferences saved');
                    $scope.me.preferences = response.data.preferences;
                })
                .catch(function (response) {
                    $scope.responseError(response);
                });
        },

        defaultColor: function ($type, $default_color) {
            if ($type === 'income') {
                $scope.colors.income = $default_color;
            }
            else if ($type === 'expense') {
                $scope.colors.expense = $default_color;
            }
            else if ($type === 'transfer') {
                $scope.colors.transfer = $default_color;
            }
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});


//$scope.$watchCollection('colors', function (newValue) {
//    $("#income-color-picker").val(newValue.income);
//    $("#expense-color-picker").val(newValue.expense);
//    $("#transfer-color-picker").val(newValue.transfer);
//});

//savePreferences: function (preferences) {
//    var url = '/api/users/' + me.id;
//    var data = {
//        preferences: preferences
//    };
//
//    return $http.put(url, data);
//},
//updateColors: function ($colors) {
//    var $url = 'api/update/colors';
//    var $description = 'colors';
//    var $data = {
//        description: $description,
//        colors: $colors
//    };
//
//    return $http.post($url, $data);
//}