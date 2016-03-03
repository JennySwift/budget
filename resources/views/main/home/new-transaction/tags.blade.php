<div v-if="newTransaction.type !== 'transfer'">
    <div class="help-row">
        <label>Add tags to your transaction (optional)</label>

        <div dropdowns-directive class="dropdown-directive">
            <button v-on:click="toggleDropdown()" tabindex="-1" class="btn btn-info btn-xs">
                Help
                <span class="caret"></span>
            </button>

            <div class="dropdown-content animated">
                <div class="help">
                    <div>To add tags to your transaction, start typing the name of your tag in the field, then use the up or down arrow keys to select a tag, then press enter.</div>
                    <div>Repeat the process to enter more than one tag.</div>
                    <div>The tag must first be created on the <a href="/tags">tags page</a> in order for it to show up as an option here.</div>
                    <div>If you press enter with no tag selected, the transaction will be entered.</div>
                </div>
            </div>
        </div>
    </div>

    <budget-autocomplete
            :chosen-budgets.sync="newTransaction.budgets"
            :budgets="budgets"
            multiple-budgets="true"
            :function-on-enter="insertTransaction"
    >
    </budget-autocomplete>

</div>
