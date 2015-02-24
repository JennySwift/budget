
<!-- ==============================navbar on the left============================== -->

<nav>
    <ul id="nav">
        <li ng-click="changeTab('settings')" id="tags_button" class="location_button fa fa-cog"></li>
        <li ng-click="changeTab('home')" class="fa fa-home"></li>
        <li class="fa fa-bar-chart-o"></li>
        
        <!-- ==============================dropdown============================== -->
        
        <li class="fa fa-bars">
            <ul>
                <li id="convert_date_button_2">Convert Date</li>
                <li id="mass-delete-button">Mass Delete</li>
                <li>Mass Edit</li>
                <li ng-click="show.color_picker = true">Colours</li>
                <li ng-click="show.credits = true">Credits</li>
            </ul>
        </li>

        <!-- ===============================.navbar-right=============================== -->
        
        <li>
            <!-- if they are a guest they shouldn't see this page anyway, but so that my code will work... -->
            <?php echo Auth::user()->name; ?>
            <ul>
                <li><a href="/auth/logout">Logout</a></li>
            </ul>
        </li>
            
        <!-- ==============================dropdown============================== -->
    
        <li class="fa fa-question">
            <ul>
                <li>Tour</li>
                <li>Tour 2</li>
                <li>How to</li>
            </ul>
        </li>

        <li ng-click="tab = 'budget'" class="fa fa-usd"></li>
        <!-- <li ng-click="show.filter = !show.filter; show.new_transaction = false" class="fa fa-search"></li> -->

    </ul>
</nav>
