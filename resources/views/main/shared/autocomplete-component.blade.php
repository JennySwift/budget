<script id="autocomplete-template" type="x-template">

    <div class="autocomplete">
        <div class="form-group autocomplete-field">
            <label v-if="inputLabel" :for="autocompleteFieldId">@{{ inputLabel | capitalize }}</label>
            <input
                    v-model="chosenOption[prop]"
                    v-on:keyup="respondToKeyup($event.keyCode)"
                    v-on:focus="respondToFocus()"
                    v-on:blur="hideDropdown()"
                    type="text"
                    :id="autocompleteFieldId"
                    :name="autocompleteFieldId"
                    :placeholder="inputPlaceholder"
                    class="form-control"
            >
        </div>

        <div
                v-show="showDropdown"
                transition="fade"
                class="autocomplete-dropdown scrollbar-container"
        >
            <div
                    v-for="option in autocompleteOptions"
                    v-show="autocompleteOptions.length > 0"
                    v-bind:class="{'selected': currentIndex === $index}"
                    v-on:mouseover="hoverItem($index)"
                    class="autocomplete-option"
                    v-on:mousedown="respondToMouseDownOnOption($index)"
            >
                <div v-on:mousedown="respondToMouseDownOnText($index)">@{{ option[prop] }}</div>

                {{--Delete button--}}
                <button
                    v-if="deleteFunction"
                    v-on:mousedown="deleteOption(option)"
                    class="btn btn-xs btn-danger"
                >
                    Delete
                </button>

                {{--Labels for option--}}
                <span v-if="option.assignedAlready && labelForOption" class="label label-default">
                        Assigned
                </span>
                <span v-if="!option.assignedAlready && labelForOption" class="label label-danger">Unassigned</span>

            </div>
            <div v-if="autocompleteOptions.length === 0" class="no-results">No results</div>
        </div>
    </div>

</script>