
<script type="text/v-template" id="feedback-template">
    <div id="feedback">
        <div
            v-repeat="feedback in feedbackMessages track by $index"
            v-class="feedback.type"
            v-bind-html="feedback.message"
            class="feedback-message">
        </div>
    </div>
</script>

