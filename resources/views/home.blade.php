<!doctype html>
<html lang="en" class="" ng-app="budgetApp">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>

    <?php
        include(base_path().'/resources/views/templates/config.php');
        include($head_links);
    ?>

</head>

<body ng-controller="mainCtrl">

<div class="main">

    <?php
        include($templates . '/header.blade.php');
        include($templates . '/messages.php');
        include($templates . '/popups/home/index.php');
    ?>
    
    <div>
    
        <?php include($templates . '/toolbar.php'); ?>
    
        <div id="new-transaction-container" class="">
            <?php
                include($templates . '/new-transaction.php');
            ?>
        </div>
        
        <div class="flex">
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
    
        </div>

    </div>
  
</div><!-- .main -->  

<?php include($footer); ?>

@include('footer')
