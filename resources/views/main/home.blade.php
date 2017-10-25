
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>
    <link rel="stylesheet" href="/css/app.css">
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
<script type="text/javascript" src="/js/app.js"></script>

</body>
</html>

