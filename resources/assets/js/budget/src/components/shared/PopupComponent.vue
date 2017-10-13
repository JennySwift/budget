<template>
    <div
        v-show="shared.showPopup"
        transition="popup-outer"
        v-on:click="closePopup($event)"
        class="popup-outer animate"
    >

        <div
            v-show="shared.showPopup"
            transition="popup-inner"
            :id="id"
            class="popup-inner animate"
        >

            <div class="flex-container">
                <div class="content">
                    <slot name="content"></slot>
                </div>

                <div class="buttons">
                    <slot name="buttons">

                    </slot>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import helpers from '../../repositories/Helpers'
    import store from '../../repositories/Store'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        computed: {

        },
        components: {},
        methods: {
            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this, this.redirectTo);
            }
        },
        props: [
            'showPopup',
            'id',
            'redirectTo'
        ]
    }

</script>

