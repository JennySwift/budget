<div>
    <div class="help-row">
        <label>Enter the total</label>

        <div dropdowns-directive class="dropdown-directive">
            <button ng-click="showDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                Help
                <span class="caret"></span>
            </button>

            <div class="dropdown-content animated">
                <div class="help">
                    <div>Enter a value without a dollar sign. No need to add a negative sign for an expense transaction.</div>
                    <div>For example:</div>
                    <div>
                        <div>5</div>
                        <div>5.5</div>
                        <div>5.55</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input
        ng-model="new_transaction.total"
        ng-keyup="insertTransaction($event.keyCode)"
        class="total mousetrap form-control"
        placeholder="$"
        type='text'>
</div>
