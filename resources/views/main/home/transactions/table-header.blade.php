<thead>

    <tr>

        <th v-show="showDate">Date</th>

        <th v-show="showDescription">Description</th>

        <th v-show="showMerchant">Merchant</th>

        <th v-show="showTotal">
            <span class="total fa fa-dollar"></span>
        </th>

        <th v-show="showAccount">Account</th>

        <th v-show="showDuration">Duration</th>

        <th v-show="showReconciled" class="reconcile">R</th>

        <th v-show="showDelete">
            <i class="fa fa-times"></i>
        </th>

        <th>
            <i class="fa fa-pencil-square-o"></i>
        </th>

        <th v-show="showAllocated">budgets</th>

    </tr>

</thead>