<div>
    @include('main.home.new-transaction.date-help')

    <input
        v-model="newTransaction.userDate"
        v-on:keyup.13="insertTransaction()"
        id="date"
        placeholder="date"
        type='text'
        class="date mousetrap form-control">
</div>

