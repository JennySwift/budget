<?php $page = "settings"; ?>

<!-- ==============================setup container============================== -->       
<div ng-show="tab === 'settings'" id="setup_container" class="container">

    <!--==============================budget============================== -->

    <!-- <div class="row">
        <span id="flex_budget_used" class="error_message" >You have given this tag a fixed budget.</span>
        <span id="fixed_budget_used" class="error_message">You have given this tag a percentage budget.</span>
        
        <div class="col-sm-6">
            <select id="fixed-budget-tag-select" class="budget-tag_select col-sm-6"><option></option></select>
            <input type="text" id="add_budget_input" class="budget_input col-sm-6">
        </div>

        <div class="col-sm-6">
            <select id="flex-budget-tag-select" class="budget-tag_select col-sm-6"><option></option></select>
            <input type="text" id="add_perc_budget_input" class="perc_budget_input col-sm-6">
        </div>
    </div> -->

    <!-- ==============================tags============================== -->    
    <div class="row">
        <div class="col-sm-4">
            <input ng-keyup="checkKeycode($event.keyCode, insertTag)" type="text" class="font-size-sm" id="new-tag-input" placeholder="new tag">
            <div id="display_tags">
                <table class="table table-bordered">
                    <tr ng-repeat="tag in tags">
                        <td>{{tag.name}}</td>
                        <td>
                            <button ng-click="updateTagSetup(tag.id, tag.name)">edit</button>
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
            <input ng-keyup="checkKeycode($event.keyCode, insertAccount)" type="text" class="new_account_input font-size-sm" id="new_account_input" placeholder="new account">
            <table class="table table-bordered">
                <tr ng-repeat="account in accounts">
                    <td>{{account.name}}</td>
                    <td>
                        <button ng-click="updateAccountSetup(account.id, account.name)">edit</button>
                    </td>
                    <td>
                        <button ng-click="deleteAccount(account.id)" class="btn btn-default">delete</button>
                    </td>
                </tr>
            </table>
        </div>
        
    </div><!-- .row -->
</div><!-- .container -->
