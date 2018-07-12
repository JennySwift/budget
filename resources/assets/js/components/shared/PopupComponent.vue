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
    import helpers from '../../repositories/helpers/Helpers'
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

<style lang="scss" type="text/scss">
    .popup-outer {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,.4);
        z-index: 999;
        display: flex;
        justify-content: center;
        align-items: center;
        .popup-inner {
            border-radius: 5px;
            background: white;
            overflow: scroll;
            max-height: 650px;
            padding: 25px;
            margin: 0 30px;
            .popup-title {
                margin-bottom: 30px;
                margin-top: 0;
            }
            .buttons {
                display: flex;
                width: 100%;
                justify-content: space-between;
                margin-top: 30px;
                button {
                    margin: 0 5px;
                }
            }
            .top-btns {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
            }
        }
    }

    @media all and (min-width: 680px) {
        .popup-inner {
            padding: 22px;
        }
    }
</style>

