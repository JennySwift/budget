<!DOCTYPE html>
<html lang="en">
<head>
    @include('templates.head')
</head>
<body>

<div id="app">
    <navbar></navbar>

    <feedback></feedback>
    <loading></loading>


    <div class="main">
        <router-view></router-view>
    </div>

    <footer>
        <li><a href="http://jennyswiftcreations.com/privacy-policy">Privacy Policy</a></li>

        <li><a href="http://jennyswiftcreations.com/credits">Credits</a></li>
    </footer>
</div>

{{--Google Analytics--}}
@include('templates.analytics-tracking')
{{--<script type="text/javascript" src="/js/app.js"></script>--}}
<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

</body>
</html>

