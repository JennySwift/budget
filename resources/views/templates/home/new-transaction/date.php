<div>
    <?php include($templates . '/home/new-transaction/date-help.php'); ?>

    <input
        ng-model="new_transaction.date.entered"
        ng-keyup="insertTransaction($event.keyCode)"
        id="date"
        placeholder="date"
        type='text'
        class="date mousetrap form-control">
</div>

