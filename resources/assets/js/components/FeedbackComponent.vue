<template>
    <div id="feedback">
        <div
            v-for="feedback in feedbackMessages" track-by="$index"
            :class="feedback.type"
            class="feedback-message"
        >

            <ul>
                <li v-for="message in feedback.messages">
                    @{{ message }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                feedbackMessages: []
            };
        },
        methods: {

            /**
             *
             * @param messages
             * @param type
             */
            provideFeedback: function (messages, type) {
                if (typeof messages === 'string') {
                    messages = [messages];
                }

                var newMessage = {
                    messages: messages,
                    type: type
                };

                var that = this;

                this.feedbackMessages.push(newMessage);

                setTimeout(function () {
                    that.feedbackMessages = _.without(that.feedbackMessages, newMessage);
                }, 3000);
            },

            /**
             *
             * @param data
             * @param status
             * @returns {*}
             */
            handleResponseError: function (data, status, response) {
                if (typeof data !== "undefined") {
                    var messages = [];

                    if (data.status) {
                        switch(data.status) {
                            case 503:
                                messages.push('Sorry, application under construction. Please try again later.');
                                break;
                            case 401:
                                messages.push('You are not logged in');
                                break;
                            case 422:
                                messages = this.setMessagesFrom422Status(data);
                                break;
                            default:
                                messages.push(data.error);
                                break;
                        }
                    }
                    else if (status) {
                        if (status === 422) {
                            messages = this.setMessagesFrom422Status(data);
                        }
                    }
                }
                else {
                    messages.push('There was an error');
                }

                return messages;

            },

            /**
             *
             * @returns {string}
             */
            setMessagesFrom422Status: function (data) {
                var messages = [];

                //for (var i = 0; i < data.length; i++) {
                //    var error = data[i];
                //    for (var j = 0; j < error.length; j++) {
                //        html += '<li>' + error[j] + '</li>';
                //    }
                //}

                $.each(data, function (key, value) {
                    var error = this;
                    for (var j = 0; j < error.length; j++) {
                        messages.push(error[j]);
                    }
                });

                return messages;
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('provide-feedback', function (event, message, type) {
                    that.provideFeedback(message, type);
                });
                $(document).on('response-error', function (event, data, status, response) {
                    that.provideFeedback(that.handleResponseError(data, status, response), 'error');
                })
            },
        },
        events: {
            'provide-feedback': function (message, type) {
                this.provideFeedback(message, type);
            },
            'response-error': function (response) {
                this.provideFeedback(this.handleResponseError(response), 'error');
            }
        },
        mounted: function () {
            this.listen();
        },
    }
</script>
