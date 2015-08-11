<div>
    <div class="help-row">
        <button ng-mousedown="insertTransaction(13)" tabindex="-1" id="add-transaction" class="btn btn-success">Add transaction</button>

        <div dropdowns-directive class="dropdown-directive">
            <button ng-click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                Help
                <span class="caret"></span>
            </button>

            <div class="dropdown-content animated">
                <div class="help">
                    <div>You can also add a new transaction by pressing enter on your keyboard when the cursor is in one of the inputs (unless pressing enter is to select an item from one of the dropdown menus).</div>
                </div>
            </div>
        </div>
    </div>

</div>