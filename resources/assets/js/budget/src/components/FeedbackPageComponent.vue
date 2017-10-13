<template>
    <div>
        <h1>Submit Feedback</h1>

        <div>
            <div class="form-group">
                <label for="new-feedback-title">Title</label>
                <input
                    v-model="newFeedback.title"
                    v-on:keyup.13="submitFeedback()"
                    type="text"
                    id="new-feedback-title"
                    name="new-feedback-title"
                    placeholder="title"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="new-feedback-body">Body</label>
                <input
                    v-model="newFeedback.body"
                    v-on:keyup.13="submitFeedback()"
                    type="text"
                    id="new-feedback-body"
                    name="new-feedback-body"
                    placeholder="body"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <button v-on:click="submitFeedback()" class="btn btn-success">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
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
                //var url = 'http://lists.dev:8000/api/items';

                this.$http.post(url, data, function (response) {
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
        mounted: function () {

        }
    }
</script>

