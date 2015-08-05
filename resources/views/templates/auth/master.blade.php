<!doctype html>

<html lang="en" class="" ng-app="budgetApp">

<head>
    <meta charset="UTF-8">
    <title>Budget App</title>
    <link rel="stylesheet" href="../tools/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        #navbar {display: flex;}
        body .main {display: block;}
    </style>
</head>

<body>
    @include('templates.header')
    <div class="main">
        @section('content')
        @show
    </div>

</body>