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

        /**
        *
        */
        updatePreferences: function () {
            $.event.trigger('show-loading');

            var data = {
                preferences: {
                    clearFields: this.me.preferences.clearFields,
                    colors: {
                        income: this.me.preferences.colors.income,
                        expense: this.me.preferences.colors.expense,
                        transfer: this.me.preferences.colors.transfer
                    }
                }
            };

            this.$http.put('/api/users/' + me.id, data, function (response) {
                this.me.preferences = response.preferences;
                $.event.trigger('provide-feedback', ['Preferences updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param type
         * @param defaultColor
         */
        defaultColor: function (type, defaultColor) {
            if (type === 'income') {
                this.me.preferences.colors.income = defaultColor;
            }
            else if (type === 'expense') {
                this.me.preferences.colors.expense = defaultColor;
            }
            else if (type === 'transfer') {
                this.me.preferences.colors.transfer = defaultColor;
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