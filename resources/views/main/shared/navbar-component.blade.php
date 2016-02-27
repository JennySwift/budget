<script id="navbar-template" type="x-template">

    <ul id="navbar" style="z-index:1000">

        <li><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

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

            <li>
                @include('main.home.show-button')
            </li>



            @include('templates.header.user')

            @include('templates.header.budgets')

            @include('templates.header.help')

            <li v-if="page === 'home'">
                <a v-on:click="toggleFilter()" href="#" class="fa fa-search"></a>
            </li>

        @endif

    </ul>
</script>


