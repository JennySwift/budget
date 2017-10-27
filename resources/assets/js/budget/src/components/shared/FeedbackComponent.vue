<template>
    <div id="feedback">

        <div
            v-for="(feedback, index) in feedbackMessages" v-bind:key="index"
            :class="feedback.type"
            class="feedback-message"
        >

            <ul>
                <li v-for="message in feedback.messages">
                    {{ message }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    var _ = require('underscore');

    export default {
        template: "#feedback-template",
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

                var feedback = {
                    messages: messages,
                    type: type
                };

                var that = this;

                this.feedbackMessages.push(feedback);

                setTimeout(function () {
                    that.feedbackMessages = _.without(that.feedbackMessages, feedback);
                }, 4000);
            },

            /**
             *
             * @param data
             * @param status
             * @returns {*}
             */
            handleResponseError: function (response) {
                console.log(response);
                var messages = [];
                var defaultMessage = 'There was an error';

                if (!response || !response.status) {
                    messages.push(defaultMessage);
                    return messages;
                }

                switch(response.status) {
                    case 503:
                        messages.push('Sorry, application under construction. Please try again later.');
                        break;
                    case 401:
                        messages.push('You are not logged in');
                        break;
                    case 422:
                        messages = this.setMessagesFrom422Status(response.data);
                        break;
                    case 400:
                        messages.push(response.data.error);
                        break;
                    default:
                        response && response.error ? messages.push(response.error) : messages.push(defaultMessage);
                        break;
                }

                return messages;
            },

            /**
             *
             * @param errors
             * @returns {Array}
             */
            setMessagesFrom422Status: function (errors) {
                var messages = [];
                var i;

                for (i in errors) {
                    for (var j = 0; j < errors[i].length; j++) {
                        messages.push(errors[i][j]);
                    }
                }

                return messages;
            }
        },
        created: function () {
            var that = this;
            this.$bus.$on('provide-feedback', this.provideFeedback);
            this.$bus.$on('response-error', function (response) {
                that.provideFeedback(that.handleResponseError(response), 'error')
            });
        },
        ready: function () {

        },
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../../../sass/variables';
    #feedback {
        position: fixed;
        right: 11px;
        top: 44px;
        z-index: 9999;
        .feedback-message {
            background: white;
            color: #777;
            line-height:40px;
            margin-bottom: 20px;
            padding: 0px 10px;
            border-radius: 4px;
            position: relative;
            border: 1px solid #777;
            box-shadow: 3px 3px 5px #777, inset 0 0 5px #777;
            &.error {
                background: #d9534f;
                color: white;
            }
            &.success {
                background: #5cb85c;
                color: white;
            }
        }
    }
</style>

