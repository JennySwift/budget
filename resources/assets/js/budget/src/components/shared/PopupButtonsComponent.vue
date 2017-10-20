<template>
    <div class="btns">
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
</template>

<script>
    import store from '../../repositories/Store'
    export default {
        methods: {
            hidePopup () {
                store.set(false, 'showPopup');
            }
        },
        props: [
            'save',
            'destroy',
            'redirectTo'
        ]
    }
</script>