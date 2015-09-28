app.factory('ErrorsFactory', function ($q) {
    return {

        responseError: function (response) {

            if(typeof response !== "undefined") {
                var $message;

                switch(response.status) {
                    case 503:
                        $message = 'Sorry, application under construction. Please try again later.';
                        break;
                    case 401:
                        $message = 'You are not logged in';
                        break;
                    case 422:
                        var html = "<ul>";
                        angular.forEach(response.data, function(value, key) {
                            var fieldName = key;
                            angular.forEach(value, function(value) {
                                html += '<li>'+value+'</li>';
                            });
                        });
                        html += "</ul>";
                        $message = html;
                        break;
                    default:
                        $message = response.data.error;
                        break;
                }
            }
            else {
                $message = 'There was an error';
            }

            return $message;

            //return $q.reject(rejection);
        }

    };
});