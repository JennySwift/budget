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
         
    <div id="setup_container" class="container">

        <!-- ==============================tags============================== -->    

        <div class="row">

            <div class="col-sm-4">
                <input ng-keyup="insertTag($event.keyCode)" type="text" class="font-size-sm" id="new-tag-input" placeholder="new tag">
                <div id="display_tags">
                    <table class="table table-bordered">
                        <tr ng-repeat="tag in tags">
                            <td>{{tag.name}}</td>
                            <td>
                                <button ng-click="showEditTagPopup(tag.id, tag.name)">edit</button>
                            </td>
                            <td>
                                <button ng-click="deleteTag(tag.id)" class="btn btn-default">delete</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div> 
               
            <!--==============================.account============================== -->

            <div class="account view_mode account col-sm-4">
                <input ng-keyup="insertAccount($event.keyCode)" type="text" class="new_account_input font-size-sm" id="new_account_input" placeholder="new account">
                <table class="table table-bordered">
                    <tr ng-repeat="account in accounts">
                        <td>{{account.name}}</td>
                        <td>
                            <button ng-click="showEditAccountPopup(account.id, account.name)">edit</button>
                        </td>
                        <td>
                            <button ng-click="deleteAccount(account.id)" class="btn btn-default">delete</button>
                        </td>
                    </tr>
                </table>
            </div>
            
        </div><!-- .row -->

    </div><!-- .container -->

    <?php include($footer); ?>

</body>
</html>