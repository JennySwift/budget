<div>
    @include('main.home.new-transaction.date-help')

    <input
        v-model="newTransaction.date.entered"
        v-on:keyup="insertTransaction($event.keyCode)"
        id="date"
        placeholder="date"
        type='text'
        class="date mousetrap form-control">
</div>

