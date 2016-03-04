
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>
    @include('main.shared.head-links')
</head>
<body>

<navbar
    :show.sync="show",
    :transaction-properties-to-show.sync="transactionPropertiesToShow",
>
</navbar>

<feedback></feedback>
<loading></loading>


<div class="main">
    <router-view
        :show="show"
        :transaction-properties-to-show="transactionPropertiesToShow"
    >
    </router-view>
</div>

@include('main.shared.footer')

</body>
</html>

