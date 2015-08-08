<div id="feedback">
    <div
        ng-repeat="feedback in feedback_messages track by $index"
        ng-class="feedback.type"
        class="feedback-message">
        [[feedback.message]]
    </div>
</div>