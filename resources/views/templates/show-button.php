
<div class="my-btn-dropdown">
    <button ng-click="show.status = !show.status" class="my-btn">Show</button>
    <ul ng-show="show.status" class="list-group bg-blue">

        <li class="list-group-item">
            <label>
                <span>new transaction</span>
                <input ng-model="show.new_transaction" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>filter</span>
                <input ng-model="show.filter" type="checkbox">
            </label>
        </li>
        
        <li class="list-group-item">
            <label>
                <span>basic totals</span>
                <input ng-model="show.basic_totals" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>advanced totals</span>
                <input ng-model="show.budget_totals" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>filter totals</span>
                <input ng-model="show.filter_totals" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>date</span>
                <input ng-model="show.date" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>description</span>
                <input ng-model="show.description" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>merchant</span>
                <input ng-model="show.merchant" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>total</span>
                <input ng-model="show.total" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>account</span>
                <input ng-model="show.account" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>reconciled</span>
                <input ng-model="show.reconciled" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>delete</span>
                <input ng-model="show.dlt" type="checkbox">
            </label>
        </li>

        <li class="list-group-item">
            <label>
                <span>tags</span>
                <input ng-model="show.tags" type="checkbox">
            </label>
        </li>

    </ul>
</div>
