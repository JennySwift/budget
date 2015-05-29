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
    ?>
    
    <h1 class="center">Feature has not been done yet.</h1>
  
</div><!-- .main -->  

<?php include($footer); ?>
