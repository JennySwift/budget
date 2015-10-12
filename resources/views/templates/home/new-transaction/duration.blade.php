<div>
    <div class="help-row">
        <label>Duration (optional)
        </label>

        <div dropdowns-directive class="dropdown-directive">
            <button ng-click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
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
            ng-model="new_transaction.duration"
            ng-keyup="insertTransaction($event.keyCode)"
            class="mousetrap form-control"
            placeholder="minutes"
            type='text'>
</div>
