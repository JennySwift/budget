
<!-- This div is so the is has same margin as other -->
<div ng-show="show.new_transaction" ng-style="{color: colors[new_transaction.type]}" id="new-transaction">
    <div class="type">
        <?php include($templates . '/home/new-transaction/type.php'); ?>
    </div>

    <div>
        <?php include($templates . '/home/new-transaction/date.php'); ?>
        <?php include($templates . '/home/new-transaction/total.php'); ?>
    </div>

    <div>
        <?php include($templates . '/home/new-transaction/merchant.php'); ?>
        <?php include($templates . '/home/new-transaction/description.php'); ?>
    </div>

    <div>
        <?php include($templates . '/home/new-transaction/accounts.php'); ?>
        <?php include($templates . '/home/new-transaction/reconcile.php'); ?>
    </div>

    <div>
        <?php include($templates . '/home/new-transaction/tags.php'); ?>
        <?php include($templates . '/home/new-transaction/enter.php'); ?>
    </div>

</div>
