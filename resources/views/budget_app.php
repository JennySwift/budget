<!doctype html>
<html lang="en" class="" ng-app="budgetApp">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>

    <?php include(base_path().'/resources/views/templates/config.php'); ?>

    <link rel="stylesheet" href="tools/bootstrap.min.css">
    <link rel="stylesheet" href="tools/bootstrap-tour.min.css">    
    <link rel="stylesheet" href="tools/tooltipster.css">  
    <link rel="stylesheet" href="tools/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body class="" ng-controller="mainCtrl">

<div class="main">

    <?php
        include($templates . '/header.blade.php');
        include($templates . '/messages.php');
        include($templates . '/settings.php');
        include($templates . '/modals.php');
        include($templates . '/edit-transaction.php');
        include($templates . '/allocation-popup.php');
        include($templates . '/budget.php');
    ?>
    
    <div ng-show="tab === 'home'" class="height-100 border-box">
    
        <div id="toolbar">
            <?php
                include($templates . '/show-button.php');
            ?>
            <button ng-click="prevResults()" type="button" id="prev-results-button" class="navigate-results-button btn btn-info navbar-btn">Prev</button>
            
            <select ng-model="filter.num_to_fetch" name="" id="">
                <option value="2">2</option>
                <option value="4">4</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
    
            <span class="badge">{{filter.display_from}} to {{filter.display_to}} of {{totals.filter.num_transactions}}</span>
    
            <button ng-click="nextResults()" type="button" id="next-results-button" class="navigate-results-button btn btn-info navbar-btn">Next</button>
            <button ng-click="resetFilter()" id="reset-search" class="btn btn-info navbar-btn">Reset Filter</button> 
        </div>
    
        <div id="new-transaction-container" class="">
            <?php
                include($templates . '/new-transaction.php');
            ?>
        </div>
        
        <div class="row height-100 border-box">
            <?php
                include($templates . '/totals.php');
                include($templates . '/transactions.php');
            ?>
            <div>
                <?php
                    include($templates . '/filter.php');
                    include($templates . '/filter-totals.php');
                ?>
            </div>
    
        </div><!-- .row  --></div>
  
</div><!-- .main -->  

<footer>
    <a href="http://jennyswiftcreations.com/privacy-policy">Privacy Policy</a>
</footer>

<?php include($templates . '/footer.php'); ?>
