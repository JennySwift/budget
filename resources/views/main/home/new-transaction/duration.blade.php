<div>
    <div class="help-row">
        <label>Duration (optional)
        </label>

        <dropdown
                inline-template
                animate-in-class="flipInX"
                animate-out-class="flipOutX"
                class="dropdown-directive"
        >
            
            <div>

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
            
        </dropdown>
    </div>

    <input
            v-model="newTransaction.duration"
            v-on:keyup.13="insertTransaction()"
            class="mousetrap form-control"
            placeholder="H:M"
            type='text'>
</div>
