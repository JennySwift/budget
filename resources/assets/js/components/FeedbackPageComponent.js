var FeedbackPage = Vue.component('feedback-page', {
    template: '#feedback-page-template',
    data: function () {
        return {
            newFeedback: {}
        };
    },
    components: {},
    methods: {

        /**
        * For submitting feedback to my lists app
        */
        submitFeedback: function () {
            $.event.trigger('show-loading');
            var data = {
                title: this.newFeedback.title,
                body: this.newFeedback.body,
                priority: this.newFeedback.priority,
            };

            this.$http.post('/api/feedback', data, function (response) {
                $.event.trigger('provide-feedback', ['Feedback submitted', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});
