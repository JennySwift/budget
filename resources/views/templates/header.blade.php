
<ul id="navbar" style="z-index:1000">

    @if (Auth::guest())
        <li>
            <a href="/auth/login">Login</a>
        </li>
        <li>
            <a href="/auth/register">Register</a>
        </li>

    @else
        <li>
            <a href="/" class="fa fa-home"></a>
        </li>

        @include('templates.header.menu')

        @include('templates.header.user')

        <li>
            <a href="/budgets" class="fa fa-usd"></a>
        </li>

        @include('templates.header.help')

        <li ng-if="page === 'home'">
            <a ng-click="toggleFilter()" href="#" class="fa fa-search"></a>
        </li>

    @endif

</ul>
