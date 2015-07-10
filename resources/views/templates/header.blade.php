
<ul id="navbar" style="z-index:1000">

    <li>
        <a href="/" class="fa fa-home"></a>
    </li>

    <li id="menu-dropdown" class="dropdown">
        <a href="#" class="dropdown-toggle fa fa-bars" data-toggle="dropdown">
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="/tags">Tags</a>
            </li>

            <li>
                <a href="/accounts">Accounts</a>
            </li>

            <li ng-click="show.color_picker = true">
                <a href="">Colours</a>
            </li>
        </ul>
    </li>

    <li>
        <a href="/charts" class="fa fa-bar-chart-o"></a>
    </li>

    <li id="menu-dropdown" class="dropdown gravatar-li">
        <a href="#" data-toggle="dropdown">
            <?php echo Auth::user()->name; ?>
        </a>
        <a href="#" data-toggle="dropdown" class="gravatar-container">
            <img ng-src="[[me.gravatar]]" class="gravatar"/>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <!-- if they are a guest they shouldn't see this page anyway, but so that my code will work... -->
            <li><a href="/auth/logout">Logout</a></li>
        </ul>
    </li>

    <li>
        <a href="/budgets" class="fa fa-usd"></a>
    </li>

    <li id="menu-dropdown" class="dropdown">
        <a href="#" data-toggle="dropdown">
            Help
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="#">Tour</a>
            </li>
            <li>
                <a href="#">Tour 2</a>
            </li>
            <li>
                <a href="#">How to</a>
            </li>
        </ul>
    </li>

    <li>
        <a ng-click="toggleFilter()" href="#" class="fa fa-search"></a>
    </li>

</ul>
