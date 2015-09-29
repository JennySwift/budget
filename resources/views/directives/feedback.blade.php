<div id="feedback">
    <div
        ng-repeat="feedback in feedbackMessages track by $index"
        ng-class="feedback.type"
        ng-bind-html="feedback.message"
        class="feedback-message">

    </div>
</div>