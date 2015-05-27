
<!-- ==============================navbar on the left============================== -->

<nav>
    <ul id="nav">
        <li>
            <a href="/settings" class="fa fa-cog"></a>
        </li>

        <li>
            <a href="/" class="fa fa-home"></a>
        </li>

        <li>
            <a href="/charts" class="fa fa-bar-chart-o"></a>
        </li>
        
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

        <li>branch:refactor</li>
            
        <!-- ==============================dropdown============================== -->
    
        <li class="fa fa-question">
            <ul>
                <li>Tour</li>
                <li>Tour 2</li>
                <li>How to</li>
            </ul>
        </li>

        <li>
            <a href="/budgets" class="fa fa-usd"></a>
        </li>

    </ul>
</nav>
