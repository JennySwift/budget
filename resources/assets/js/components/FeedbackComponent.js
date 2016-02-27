
Vue.component('feedback', {
    template: "#feedback-template",
    data: function () {
        return {
            feedbackMessages: []
        };
    },
    methods: {
        listen: function () {
            var that = this;
            $(document).on('provide-feedback', function (event, message, type) {
                that.provideFeedback(message, type);
            });
            $(document).on('response-error', function (event, response) {
                that.provideFeedback(that.handleResponseError(response), 'error');
            })
        },
        provideFeedback: function (message, type) {
            var newMessage = {
                message: message,
                type: type
            };

            var that = this;

            this.feedbackMessages.push(newMessage);

            setTimeout(function () {
                that.feedbackMessages = _.without(that.feedbackMessages, newMessage);
            }, 3000);
        },
        handleResponseError: function (response) {
            if (typeof response !== "undefined") {
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

                        for (var i = 0; i < response.length; i++) {
                            var error = response[i];
                            for (var j = 0; j < error.length; j++) {
                                html += '<li>' + error[j] + '</li>';
                            }
                        }

                        html += "</ul>";
                        $message = html;
                        break;
                    default:
                        $message = response.error;
                        break;
                }
            }
            else {
                $message = 'There was an error';
            }

            return $message;

        }
    },
    events: {
        'provide-feedback': function (message, type) {
            this.provideFeedback(message, type);
        },
        'response-error': function (response) {
            this.provideFeedback(this.handleResponseError(response), 'error');
        }
    },
    ready: function () {
        this.listen();
    },
});