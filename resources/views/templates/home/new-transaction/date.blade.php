<div>
    @include('templates.home.new-transaction.date-help')

    <input
        ng-model="new_transaction.date.entered"
        ng-keyup="insertTransaction($event.keyCode)"
        id="date"
        placeholder="date"
        type='text'
        class="date mousetrap form-control">
</div>

