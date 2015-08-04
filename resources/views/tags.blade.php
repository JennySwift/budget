<!DOCTYPE html>
<html lang="en" ng-app="budgetApp">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>

    <?php
        include(base_path().'/resources/views/templates/config.php');
        include($head_links);
    ?>

</head>

@include('templates.page-loading')

<body ng-controller="BaseController">

    @include('templates.loading')
    @include('templates.header')
    @include('templates.popups.settings.index')
    
    <div ng-controller="TagsController" class="main">

        @include('templates.feedback')

        <div id="tags">

            <input ng-keyup="insertTag($event.keyCode)" type="text" class="font-size-sm center margin-bottom" id="new-tag-input" placeholder="new tag">
            
            <table class="table table-bordered">
                <tr ng-repeat="tag in tags">
                    <td>[[tag.name]]</td>
                    <td>
                        <button ng-click="showEditTagPopup(tag.id, tag.name)">edit</button>
                    </td>
                    <td>
                        <button ng-click="deleteTag(tag.id)" class="btn btn-default">delete</button>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    @include('templates/footer')
    @include('footer')
    @include('templates/tags/footer')

</body>
</html>