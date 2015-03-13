<!doctype html>
<html lang="en" class="" ng-app="budgetApp">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>

    <?php include(app_path().'/inc/config.php'); ?>

    <link rel="stylesheet" href="tools/bootstrap.min.css">
    <link rel="stylesheet" href="tools/bootstrap-tour.min.css">    
    <link rel="stylesheet" href="tools/tooltipster.css">  
    <link rel="stylesheet" href="tools/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body class="" ng-controller="mainCtrl">

<?php
    include($inc . '/header.blade.php');
    include($inc . '/messages.php');
    include($inc . '/settings.php');
    include($inc . '/modals.php');
    include($inc . '/edit-transaction.php');
    include($inc . '/allocation-popup.php');
    include($inc . '/budget.php');
?>

<p>savings branch merge test</p>
<p>cherry pick test</p>

<div ng-show="tab === 'home'" class="height-100 border-box">

    <div id="toolbar">
        <?php
            include($inc . '/show-button.php');
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
            include($inc . '/new-transaction.php');
        ?>
    </div>
    
    <div class="row height-100 border-box">
        <?php
            include($inc . '/totals.php');
            include($inc . '/transactions.php');
        ?>
        <div>
            <?php
                include($inc . '/filter.php');
                include($inc . '/filter-totals.php');
            ?>
        </div>

    </div><!-- .row  -->
  
</div><!-- .main -->  

<?php include($inc . '/footer.php'); ?>
