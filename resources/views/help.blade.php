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

<body>

<div class="main">

    @include('templates.header')

    <div id="feedback">
        <div ng-repeat="message in feedback_messages track by $index" class="feedback-message">
            [[message]]
        </div>
    </div>

    <div id="help">
        <div>
            <h2>Concept/Purpose/Goal</h2>
            <p>My brother wasn't aware of any budgeting apps for users who do not have a fixed income. In other words, whose income is unknown. It can vary from week to week. Apparently, other budgeting apps assume the user knows what their income will be.</p>
            <p>He had predetermined expenses, so he didn't want to overspend on the less important expenses and then not have enough to spend on the predetermined expenses.</p>
            <p>So he wanted to be able to specify in advance his predetermined expenses, and then for each of his remaining expense categories, to be able to specify a percentage of what he had remaining to spend on those.</p>
            <p>We call the 'predetermined' expenses 'fixed', and the others 'flex', i.e., flexible, because they are a percentage of what is remaining.</p>

            <h2>Tags</h2>
            <p>A tag is a category of something you spend or earn money on. For example, food or clothes. It is these tags that you specify a budget for. You choose the budget type (either fixed or flex).</p>
            <p>For a fixed budget, you specify the amount per month you need to spend on that tag.</p>
            <p>For a flex budget, you specify the percentage of what is remaining that you want to spend on that tag.</p>
            <p>You can specify a starting date for a tag.</p>
            <p>For the tags with fixed budgets, the starting date is taken into account. The amount calculated (column C for 'cumulative') is the amount (column A) multipled by the month number (column CMN).</p>
            <p>We're not sure if the starting date for tags with flex budgets has a point yet. :)</p>

            <h2>Transactions</h2>
            <p>A transaction can be given multiple tags. However, if more than one of those tags has been given a budget, you'll need to specify the amounts (either a fixed amount or a percentage of the total of the transaction) that you want to be considered spent off your budgeted tags.</p>
            <p>It wouldn't make sense, and it would wreck up the totals, if you were to spend $10 and have $20 considered as spent because the transaction had two budgets associated with it.</p>

            <h2>Savings</h2>
            <p>This feature is relatively new and still has plenty of work to be done on. When income is earned, a percentage of what is earned is taken from the balance and put into savings.</p>
            <p>Currently it is 10%, but it is planned to make that customisable.</p>
            <p>The action is reversed when an income transaction is deleted (and on other similar occasions).</p>

            <h2>RB</h2>
            <p>RB is the remaining balance, before the flex budgets are calculated. It is the figure that the flex budgets are a percentage of.</p>
            <p>It has been perhaps the trickiest and most important figure to figure out, since the flexible budgets depend on it, and the flexible budgets are the point of the app!</p>
            <p>We're still not sure if we have it right, but currently the formula is:</p>
            <ul>
                <li>+ total credit ever earned</li>
                <li>- remaining fixed budget</li>
                <li>- total expenses with no associated budget</li>
                <li>- total spent on flex budgets before their starting dates</li>
                <li>- total spent on flex budgets after their starting dates</li>
                <li>- total spent on fixed budgets before their starting dates</li>
                <li>- total spent on fixed budgets after their starting dates</li>
                <li>- savings</li>
            </ul>

            <h2>Totals</h2>
            <p>A lot of the figures in the totals sidebar are probably unnecessary for users, but they have been helpful in getting the totals working and understanding what is going on. Hover the totals to see explanations of what they are (or to get confused :)). A lot of them were for figuring out RB. Some are just the same figures as in the budget table totals columns.</p>
            <p>Probably the most relevant figures for the user are: credit, debit, RB, savings, and the R (remaining) columns in the budget tables, so the user can see how much they have left to spend on each tag.</p>
        </div>
    </div>

</div>

@include('templates/footer')
@include('templates/home/footer')

@include('footer')
