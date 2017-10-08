<script id="navbar-template" type="x-template">

    <ul id="navbar" style="z-index:1000">

        <li><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

        @if (Auth::guest())
            <li>
                <a href="/login">Login</a>
            </li>
            <li>
                <a href="/register">Register</a>
            </li>

        @else
            <li>
                <a
                    v-link="{path: '/'}"
                    class="fa fa-home"
                >
                </a>
            </li>

            <li>
                <a
                        v-link="{path: '/graphs'}"
                >
                    Graphs
                </a>
            </li>

            @include('templates.header.menu')

            @include('templates.header.show')

            @include('templates.header.user')

            @include('templates.header.budgets')

            @include('templates.header.help')

            <li>
                <a v-on:click="toggleFilter()" class="fa fa-search"></a>
            </li>

        @endif

    </ul>
</script>


