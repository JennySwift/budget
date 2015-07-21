<div class="help-row">
    <label>Enter the transaction's date</label>

    <div dropdowns-directive class="dropdown-directive">
        <button ng-click="showDropdown()" class="btn btn-info btn-xs">
            Help
            <span class="caret"></span>
        </button>

        <div class="dropdown-content animated">
            <div class="help">
                <h3>Acceptable formats</h3>

                <div>
                    <div>Days and months are case-insensitive and can be abbreviated (eg: mon or jan) or written in
                        full.
                    </div>
                    <div>Years can be written as either yy or yyyy.</div>
                </div>

                <div>
                    <div>today</div>
                    <div>yesterday</div>
                    <div>tomorrow</div>
                    <div>next Thursday</div>
                    <div>last Thursday</div>
                    <div>Thursday (The most recent Thursday)</div>
                    <div>date/month (In the current year. Example: 31/12, or 1/1.)</div>
                    <div>date/month/year</div>
                    <div>12 Jan (In the current year)</div>
                    <div>12 Jan 15</div>
                </div>
            </div>
        </div>
    </div>
</div>