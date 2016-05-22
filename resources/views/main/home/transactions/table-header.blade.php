<thead>

    <tr>

        <th v-show="transactionPropertiesToShow.date">Date</th>

        <th v-show="transactionPropertiesToShow.description">Description</th>

        <th v-show="transactionPropertiesToShow.merchant">Merchant</th>

        <th v-show="transactionPropertiesToShow.total">
            <span class="total fa fa-dollar"></span>
        </th>

        <th v-show="transactionPropertiesToShow.account">Account</th>

        <th v-show="transactionPropertiesToShow.duration">Duration</th>

        <th v-show="transactionPropertiesToShow.reconciled" class="reconcile">R</th>

        <th v-show="transactionPropertiesToShow.allocated">Allocation</th>

    </tr>

</thead>