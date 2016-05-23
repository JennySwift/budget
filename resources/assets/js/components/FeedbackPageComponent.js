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
        //submitFeedback: function () {
        //    $.event.trigger('show-loading');
        //    var data = {
        //        title: this.newFeedback.title,
        //        body: this.newFeedback.body,
        //        priority: this.newFeedback.priority,
        //    };
        //
        //    this.$http.post('/api/feedback', data, function (response) {
        //        $.event.trigger('provide-feedback', ['Feedback submitted', 'success']);
        //        $.event.trigger('hide-loading');
        //    })
        //    .error(function (response) {
        //        HelpersRepository.handleResponseError(response);
        //    });
        //},

        /**
         * For submitting feedback to my lists app
         */
        submitFeedback: function () {
            $.event.trigger('show-loading');

            var data = {
                title: this.newFeedback.title,
                body: this.newFeedback.body,
                priority: 1,
                //The id of my budget app item in my lists app
                parent_id: 468,
                //The id of my coding category in my lists app
                category_id: 1,
                favourite: 0,
                pinned: 0,
            };

            var url = 'http://lists.jennyswiftcreations.com/api/items';

            // this.$http.post(url, data, function (response) {
            //         $.event.trigger('provide-feedback', ['Feedback submitted', 'success']);
            //         $.event.trigger('hide-loading');
            //     })
            //     .error(function (response) {
            //         HelpersRepository.handleResponseError(response);
            //     });
        },

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});
