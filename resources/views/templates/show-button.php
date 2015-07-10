
<div class="my-btn-dropdown">
    <button ng-click="show.status = !show.status" class="btn btn-info">
        Show
        <span class="caret"></span>
    </button>
    <div ng-cloak ng-show="show.status" class="dropdown-content">

        <div>
            <div>
                <label>
                    <span>new transaction</span>
                    <input ng-model="show.new_transaction" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>filter</span>
                    <input ng-model="show.filter" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>basic totals</span>
                    <input ng-model="show.basic_totals" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>advanced totals</span>
                    <input ng-model="show.budget_totals" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>filter totals</span>
                    <input ng-model="show.filter_totals" type="checkbox">
                </label>
            </div>

        </div>

        <div>
            <div>
                <label>
                    <span>date</span>
                    <input ng-model="show.date" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>description</span>
                    <input ng-model="show.description" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>merchant</span>
                    <input ng-model="show.merchant" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>total</span>
                    <input ng-model="show.total" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>account</span>
                    <input ng-model="show.account" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>reconciled</span>
                    <input ng-model="show.reconciled" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>delete</span>
                    <input ng-model="show.dlt" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>tags</span>
                    <input ng-model="show.tags" type="checkbox">
                </label>
            </div>

        </div>

    </div>
</div>
