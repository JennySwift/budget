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
        <li><a ng-click="deleteUser()" href="#">Delete account</a></li>
    </ul>
</li>