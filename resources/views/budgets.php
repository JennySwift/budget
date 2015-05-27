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
<body ng-controller="budgets">

    <?php
        include($header);
        include($templates . '/popups/budget/index.php');
    ?>
    
    <!-- if I used ng-if here, tooltipster didn't work. -->
    <div id="budget" class="">

    
        <?php include($templates . '/totals.php'); ?>

        <div>

            <!-- ==============================savings============================== -->

            <div class="margin-bottom">
                <input ng-keyup="addFixedToSavings($event.keyCode)" type="text" placeholder="add fixed amount to savings" id="add-fixed-to-savings">
                <input ng-keyup="addPercentageToSavings($event.keyCode)" type="text" placeholder="add percentage of RB to savings" id="add-percentage-to-savings">
            </div>

            <!-- ==============================fixed budget inputs============================== -->
            
            <!-- ================tag wrapper================ -->

            <div class="tag-wrapper">
                <div class="tag-input-wrapper">
                    
                    <input ng-model="new_fixed_budget.tag.name" ng-focus="new_fixed_budget.dropdown = true" ng-blur="new_fixed_budget.dropdown = false" ng-keyup="filterTags($event.keyCode, new_fixed_budget.tag.name, new_fixed_budget.tag, 'new_fixed_budget')" placeholder="tag" id="budget-fixed-tag-input" type='text'>
                    
                    <div ng-show="new_fixed_budget.dropdown" class="tag-dropdown">
                        <li ng-repeat="tag in autocomplete.tags" ng-class="{'selected': tag.selected}">{{tag.name}}</li>
                    </div>
                
                </div>
            </div>
            
            <input ng-model="new_fixed_budget.budget" ng-keyup="updateFixedBudget($event.keyCode)" id="budget-fixed-budget-input" type="text">
                  
            <!-- ==============================fixed budget table============================== -->
            
            <table id="fixed-budget-info-table" class="table table-bordered">
                <caption class="bg-dark">Fixed Budget Info</caption>
                <!-- table header -->
                <tr>
                    <th class="tag">Tag</th>
                    <th class="tooltipster" title="amount">A</th>
                    <th class="tooltipster" title="cumulative starting date">CSD</th>
                    <th class="tooltipster" title="cumulative month number">CMN</th>
                    <th class="tooltipster" title="cumulative (amount * cumulative month number)">C</th>
                    <th class="tooltipster" title="spent before cumulative starting date">-</th>
                    <th class="tooltipster" title="spent since cumulative starting date">-</th>
                    <th class="tooltipster" title="received since cumulative starting date">+</th>
                    <th class="tooltipster" title="remaining  (cumulative + spent + received)">R</th>
                    <th>x</th>
                </tr>
            
                <!-- table content -->
                <tr ng-repeat="tag in totals.budget.FB.each_tag" class="budget_info_ul">
            
                    <td class="budget-tag">{{tag.name}}</td>
            
                    <td class="amount right">{{tag.budget}}</td>
            
                    <td class="CSD">
                        <span>{{tag.CSD}}</span>
                        <button ng-click="updateCSDSetup(tag)" class="edit">edit</button>
                    </td>
            
                    <td class="month-number">{{tag.CMN}}</td>
            
                    <td class="cumulative">{{tag.cumulative_budget}}</td>
            
                    <td class="spent">
                        <div>{{tag.spent_before_CSD}}</div>      
                    </td>
            
                    <td class="spent">
                        <div>{{tag.spent}}</div>      
                    </td>
            
                    <td class="received">{{tag.received}}</td>
            
                    <td class="remaining">{{tag.remaining}}</td>
            
                    <td ng-click="removeFixedBudget(tag.id, tag.name)" class="pointer">x</td>
            
                </tr>
            
                <!-- fixed budget totals -->
                <tr id="fixed-budget-totals" class="budget_info_ul totals">
                    <td>totals</td>
                    <td>{{totals.budget.FB.totals.budget}}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>{{totals.budget.FB.totals.cumulative_budget}}</td>
                    <td>{{totals.budget.FB.totals.spent_before_CSD}}</td>
                    <td>{{totals.budget.FB.totals.spent}}</td>
                    <td>{{totals.budget.FB.totals.received}}</td>
                    <td>{{totals.budget.FB.totals.remaining}}</td>
                    <td>-</td>
                </tr>
            
            </table>
                
            <!-- ==============================flex budget inputs============================== -->
            
            <!-- ================tag wrapper================ -->
            
            <div class="tag-wrapper">
                <div class="tag-input-wrapper">
                    
                    <input ng-model="new_flex_budget.tag.name" ng-focus="new_flex_budget.dropdown = true" ng-blur="new_flex_budget.dropdown = false" ng-keyup="filterTags($event.keyCode, new_flex_budget.tag.name, new_flex_budget.tag, 'new_flex_budget')" placeholder="tag" id="budget-flex-tag-input" type='text'>
                    
                    <div ng-show="new_flex_budget.dropdown" class="tag-dropdown">
                        <li ng-repeat="tag in autocomplete.tags" ng-class="{'selected': tag.selected}">{{tag.name}}</li>
                    </div>
                
                </div>
            </div>
            
            <input ng-model="new_flex_budget.budget" ng-keyup="updateFlexBudget($event.keyCode)" id="budget-flex-budget-input" type="text">
            
            <!-- ==============================flex budget table============================== -->
            
            <table id ="flex-budget-info-table" class="table table-bordered">
                <caption class="bg-dark">Flex Budget Info</caption>
                <!-- table header -->
                <tr>
                    <th class="tag">Tag</th>
                    <th class="tooltipster" title="amount (% column % of F/I)">A</th>
                    <th class="tooltipster" title="cumulative starting date">CSD</th>
                    <th class="tooltipster" title="cumulative month number">CMN</th>
                    <th class="tooltipster" title="spent">-</th>
                    <th class="tooltipster" title="received">+</th>
                    <th class="tooltipster" title="remaining">R</th>
                    <th class="tooltipster" title="# percent of F/I">%</th>
                    <th>x</th>
                </tr>
                <!-- table content -->
                <tr ng-repeat="tag in totals.budget.FLB.each_tag" class="budget_info_ul">
                    <td class="budget-tag">{{tag.name}}</td>
                    <td class="amount">{{tag.calculated_budget}}</td>
                    <td class="CSD">
                        <span>{{tag.CSD}}</span>
                        <button ng-click="updateCSDSetup(tag)" class="edit">edit</button>
                    </td>
                    <td class="month-number">{{tag.CMN}}</td>
                    <td class="spent">{{tag.spent}}</td>
                    <td class="received">{{tag.received}}</td>
                    <td class="remaining">{{tag.remaining}}</td>
                    <td class="percent">{{tag.budget}}</td>
                    <td ng-click="removeFlexBudget(tag.id, tag.name)" class="pointer">x</td>
                </tr>
                <!-- flex budget totals -->
                <tr id="flex-budget-totals" class="budget_info_ul totals">
                    <td>totals</td>
                    <td>{{totals.budget.FLB.totals.calculated_budget}}</td>
                    <td>-</td>
                    <td>-</td>
                    <td>{{totals.budget.FLB.totals.spent}}</td>
                    <td>{{totals.budget.FLB.totals.received}}</td>
                    <td>{{totals.budget.FLB.totals.remaining}}</td>
                    <td>{{totals.budget.FLB.totals.budget}}</td>
                    <td>-</td>
                </tr>
            </table>
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>

    <?php include($footer); ?>

</body>
</html>