
<template id="feedback-template">
    <div id="feedback">
        <div
                v-for="feedback in feedbackMessages" track-by="$index"
                :class="feedback.type"
                class="feedback-message"
        >

            <ul>
                <li v-for="message in feedback.messages">
                    @{{ message }}
                </li>
            </ul>
        </div>
    </div>
</template>