<thead>

    <tr>

        <th v-show="show.date">Date</th>

        <th v-show="show.description">Description</th>

        <th v-show="show.merchant">Merchant</th>

        <th v-show="show.total">
            <span class="total fa fa-dollar"></span>
        </th>

        <th v-show="show.account">Account</th>

        <th v-show="show.duration">Duration</th>

        <th v-show="show.reconciled" class="reconcile">R</th>

        <th v-show="show.dlt">
            <i class="fa fa-times"></i>
        </th>

        <th>
            <i class="fa fa-pencil-square-o"></i>
        </th>

        <th v-show="show.allocated">budgets</th>

    </tr>

</thead>