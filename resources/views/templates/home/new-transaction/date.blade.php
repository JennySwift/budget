<div>
    @include('templates.home.new-transaction.date-help')

    <input
        v-model="new_transaction.date.entered"
        v-on:keyup="insertTransaction($event.keyCode)"
        id="date"
        placeholder="date"
        type='text'
        class="date mousetrap form-control">
</div>

