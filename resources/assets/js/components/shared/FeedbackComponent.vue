<template>
    <div id="feedback">

        <div
            v-for="(notification, index) in shared.notifications" v-bind:key="index"
            :class="notification.type"
            class="feedback-message"
        >
            <div>{{notification.message}}</div>
            <!--<ul>-->
                <!--<li v-for="message in feedback.messages">-->
                    <!--{{ message }}-->
                <!--</li>-->
            <!--</ul>-->
        </div>

        <!--<div-->
            <!--v-for="(feedback, index) in shared.feedbackMessages" v-bind:key="index"-->
            <!--:class="feedback.type"-->
            <!--class="feedback-message"-->
        <!--&gt;-->
            <!---->
            <!--<ul>-->
                <!--<li v-for="message in feedback.messages">-->
                    <!--{{ message }}-->
                <!--</li>-->
            <!--</ul>-->
        <!--</div>-->
    </div>
</template>

<script>
    var _ = require('underscore');

    export default {
        data: function () {
            return {
                shared: store.state
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
    @import '../../../sass/variables';
    #feedback {
        position: fixed;
        top: 0;
        z-index: 9999;
        width: 100%;
        .feedback-message {
            background: white;
            color: #777;
            line-height:40px;
            padding: 2px 10px;
            text-align: center;
            position: relative;
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

