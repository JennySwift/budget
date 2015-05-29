
<!-- ==============================navbar on the left============================== -->

<nav>
    <ul id="nav">

        <!-- ==============================home============================== -->

        <li>
            <a href="/" class="fa fa-home"></a>
        </li>
        
        <!-- ==============================dropdown============================== -->
        
        <li class="fa fa-bars">
            <ul class="hover-parent">
                <li>
                    <a href="/tags">Tags</a>
                </li>

                <li>
                    <a href="/accounts">Accounts</a>
                </li>

                <li ng-click="show.color_picker = true">Colours</li>
                <li ng-click="show.credits = true">Credits</li>
            </ul>
        </li>

        <!-- ==============================chart============================== -->

        <li>
            <a href="/charts" class="fa fa-bar-chart-o"></a>
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

        <li>
            <a ng-click="toggleFilter()" href="#" class="fa fa-search"></a>
        </li>

    </ul>
</nav>
