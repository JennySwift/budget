<template>
    <div
        v-show="shared.show.popup[popupName]"
        transition="popup-outer"
        v-on:click="closePopup($event)"
        class="popup-outer animate"
    >

        <div
            v-show="shared.show.popup[popupName]"
            transition="popup-inner"
            :id="id"
            class="popup-inner animate"
        >

            <div class="flex-container">
                <div class="content">
                    <slot name="content"></slot>
                </div>

                <div class="buttons">
                    <router-link
                        v-if=redirectTo
                        v-on:click.native="hidePopup"
                        :to="redirectTo"
                        tag="button"
                        class="btn btn-default"
                    >
                        Cancel
                    </router-link>

                    <button
                        v-if="!redirectTo"
                        v-on:click="hidePopup()"
                        class="btn btn-default"
                    >
                        Cancel
                    </button>

                    <button
                        v-if="destroy"
                        v-on:click="destroy()"
                        class="btn btn-danger"
                    >
                        Delete
                    </button>

                    <button
                        v-if="save"
                        v-on:click="save()"
                        class="btn btn-success"
                    >
                        Save
                    </button>
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
            showPopup: function () {
                return this.shared.show.popup[this.popupName];
            }
        },
        components: {},
        methods: {
            /**
             *
             */
            closePopup: function ($event) {
                helpers.closePopup($event, this, this.redirectTo);
            },
            hidePopup () {
                helpers.hidePopup(this.popupName);
            }
        },
        props: [
            'popupName',
            'id',
            'save',
            'destroy',
            'redirectTo'
        ]
    }

</script>

