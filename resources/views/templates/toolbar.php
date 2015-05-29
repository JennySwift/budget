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

    <?php include($templates . '/actions-button.php'); ?>
    
</div>