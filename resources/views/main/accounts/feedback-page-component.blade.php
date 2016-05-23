<script id="feedback-page-template" type="x-template">

<div>
    <h1>Submit Feedback</h1>

    <h3>Not available in demo version</h3>

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

        {{--<div class="form-group">--}}
            {{--<label for="new-feedback-priority">Priority</label>--}}
            {{--<input--}}
                {{--v-model="newFeedback.priority"--}}
                {{--type="text"--}}
                {{--id="new-feedback-priority"--}}
                {{--name="new-feedback-priority"--}}
                {{--placeholder="priority"--}}
                {{--class="form-control"--}}
            {{-->--}}
        {{--</div>--}}

        <div class="form-group">
            <button v-on:click="submitFeedback()" class="btn btn-success">Submit</button>
        </div>
    </div>
</div>

</script>