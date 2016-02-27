<div>
    <div class="help-row">
        <label>Duration (optional)
        </label>

        <div dropdowns-directive class="dropdown-directive">
            <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                Help
                <span class="caret"></span>
            </button>

            <div class="dropdown-content animated">
                <div class="help">
                    <div>Enter the duration in H:M format.</div>
                </div>
            </div>
        </div>
    </div>

    <input
            v-model="newTransaction.duration"
            v-on:keyup.13="insertTransaction()"
            class="mousetrap form-control"
            placeholder="H:M"
            type='text'>
</div>
