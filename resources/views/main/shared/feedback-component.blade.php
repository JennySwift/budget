
<template id="feedback-template">
    <div id="feedback">
        <div
                v-for="feedback in feedbackMessages" track-by="$index"
                :class="feedback.type"
                class="feedback-message"
                v-text="feedback.message">
        </div>
    </div>
</template>