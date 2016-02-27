
<div class="my-btn-dropdown">
    <button v-on:click="show.status = !show.status" class="btn btn-info">
        Show
        <span class="caret"></span>
    </button>
    <div v-cloak v-show="show.status" class="dropdown-content">

        <div>
            <div>
                <label>
                    <span>new transaction</span>
                    <input v-model="show.new_transaction" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>filter</span>
                    <input v-model="show.filter" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>basic totals</span>
                    <input v-model="show.basic_totals" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>advanced totals</span>
                    <input v-model="show.budget_totals" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>filter totals</span>
                    <input v-model="show.filter_totals" type="checkbox">
                </label>
            </div>

        </div>

        <div>
            <div>
                <label>
                    <span>date</span>
                    <input v-model="show.date" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>description</span>
                    <input v-model="show.description" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>merchant</span>
                    <input v-model="show.merchant" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>total</span>
                    <input v-model="show.total" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>account</span>
                    <input v-model="show.account" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>duration</span>
                    <input v-model="show.duration" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>reconciled</span>
                    <input v-model="show.reconciled" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>allocated</span>
                    <input v-model="show.allocated" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>delete</span>
                    <input v-model="show.dlt" type="checkbox">
                </label>
            </div>

            <div>
                <label>
                    <span>tags</span>
                    <input v-model="show.tags" type="checkbox">
                </label>
            </div>

        </div>

    </div>
</div>
