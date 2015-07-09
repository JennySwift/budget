<!DOCTYPE html>
<html lang="en" ng-app="budgetApp">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>

    <?php
        include(base_path().'/resources/views/templates/config.php');
        include($head_links);
    ?>

</head>
<body ng-controller="settings">

    <?php
        include($header);
        include($templates . '/popups/settings/index.php');
    ?>
         
    <div class="main">

        <div id="accounts">

            <input ng-keyup="insertAccount($event.keyCode)" type="text" class="new_account_input font-size-sm center margin-bottom" id="new_account_input" placeholder="new account">
            
            <table class="table table-bordered">
                <tr ng-repeat="account in accounts">
                    <td>[[account.name]]</td>
                    <td>
                        <button ng-click="showEditAccountPopup(account.id, account.name)">edit</button>
                    </td>
                    <td>
                        <button ng-click="deleteAccount(account.id)" class="btn btn-default">delete</button>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <?php include($footer); ?>

    @include('footer')

</body>
</html>